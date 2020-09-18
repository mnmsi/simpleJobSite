<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestUri = explode('/', url()->current());
        $requestUri = end($requestUri);

        if ($requestUri == 'dashboard' || strstr(url()->current(), '/profileView/') || strstr(url()->current(), '/jobView/')) {
            return $next($request);
        }

        $userUrlPermissionArr = array(
            'profile', 'editProfile', 'apply', 'appliedJob'
        );

        if (in_array($requestUri, $userUrlPermissionArr)) {
            if (Auth::user()->role !== 'Employee') {

                $notification = array(
                    'message'    => 'You have no permission to access that url.',
                    'alert-type' => 'warning'
                );

                return redirect('/dashboard')->with($notification);
            }
        } else {
            if (Auth::user()->role !== 'Hirer') {

                $notification = array(
                    'message'    => 'You have no permission to access that url.',
                    'alert-type' => 'warning'
                );

                return redirect('/dashboard')->with($notification);
            }
        }

        return $next($request);
    }
}
