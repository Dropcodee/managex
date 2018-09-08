<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $request = app()->make('request');
            # the request instance
            $loggedInUser = $request->user();
            # get the authenticated user, if any
            if ($loggedInUser) {
                $company = $loggedInUser->company(true, true);
                $view->with('loggedInUser', $loggedInUser);
                $view->with('loggedInUserRole', 'Developer');
                $view->with('loggedInUserCompany', !empty($company) && !empty($company->id) ? $company : null);
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
