<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;


class TrustProxies extends Middleware
{
    protected $proxies;
    protected $headers = \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_AWS_ELB;
}
