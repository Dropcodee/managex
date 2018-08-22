<?php

namespace App\Http\Middleware;

use Closure;
use Hostville\Dorcas\LaravelCompat\Auth\DorcasUser;
use Hostville\Dorcas\Sdk;
use Illuminate\Support\Facades\Auth;

class DorcasAuthViaUrlToken
{
    const PAGE_MODE_MOBILE = 'mobile';
    const PAGE_MODE_DEFAULT = 'default';
    
    /** @var array  */
    protected $pageModes = [self::PAGE_MODE_MOBILE, self::PAGE_MODE_DEFAULT];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->query->has('token')) {
            return $next($request);
        }
        $token = $request->query('token');
        $sdk = app(Sdk::class);
        $sdk->setAuthorizationToken($token);
        # instantiate the sdk
        $query = $sdk->createProfileService()->send('get');
        if (!$query->isSuccessful()) {
            # we couldn't load the profile
            return $next($request);
        }
        $user = new DorcasUser($query->getData(), $sdk);
        $guard = Auth::guard();
        # the session guard
        $request->session()->put($guard->getName(), $user->getAuthIdentifier());
        $request->session()->migrate(true);
        if (isset($guard->events)) {
            $guard->events->dispatch(new \Illuminate\Auth\Events\Login($user, false));
        }
        $guard->setUser($user);
        if ($request->query->has('mode')) {
            # we have a page mode
            $pageMode = strtolower($request->query('mode'));
            if (in_array($pageMode, $this->pageModes)) {
                $request->session()->put('pageMode', $pageMode);
            }
        } else {
            $request->session()->put('pageMode', self::PAGE_MODE_DEFAULT);
        }
        return $next($request);
    }
}
