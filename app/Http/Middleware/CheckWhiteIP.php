<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 9:33
 */

namespace App\Http\Middleware;

use Closure;

class CheckWhiteIP
{

    public function handle($request, Closure $next)
    {
        if (!in_array($request->getClientIp(), ['123.123.123.123', '124.124.124.124']))
        {
            return response('bad request', 503);
        }
        return $next($request);
    }

}