<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $authentication = true;
        if (!$token) {
            $authentication = false;
        } else {
            $user = User::firstWhere('token', $token);

            if (!$user) {
                $authentication = false;
            } else {
                Auth::login($user);
            }
        }

        if ($authentication) {
            return $next($request);
        } else {
            return response()->json([
                'errors' => [
                    'messages' => ['Unauthorized']
                ]
            ], 401);
        }
    }
}
