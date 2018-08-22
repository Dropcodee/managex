<?php

namespace App\Http\Controllers;

use Hostville\Dorcas\Sdk;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Returns the sub-domains belonging to the currently authenticated business.
     *
     * @param Sdk|null $sdk
     *
     * @return mixed
     */
    public function getSubDomains(Sdk $sdk = null)
    {
        $sdk = $sdk ?: app(Sdk::class);
        $company = auth()->user()->company(true, true);
        # get the company
        $subdomains = Cache::remember('subdomains.'.$company->id, 30, function () use ($sdk) {
            $response = $sdk->createDomainResource()->addQueryArgument('limit', 1000)->send('get', ['issuances']);
            if (!$response->isSuccessful()) {
                return null;
            }
            return collect($response->getData())->map(function ($subdomain) {
                return (object) $subdomain;
            });
        });
        return $subdomains;
    }
}
