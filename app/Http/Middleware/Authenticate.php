<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{



    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('login');
    }

//    protected function unauthenticated($request, array $guards)
//    {
////        throw new AuthenticationException(
////            'Unauthenticated.', $guards, $this->redirectTo($request)
////        );
//
//        return response()->json(['message' => 'Unauthenticated.'], 401);
//    }
}
