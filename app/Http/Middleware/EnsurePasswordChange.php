<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * Bloqueia o acesso quando o usuário precisa trocar a senha,
     * permitindo apenas as rotas de troca de senha e logout.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->must_change_password) {
                $allowedRouteNames = [
                    'password.first_login.edit',
                    'password.first_login.update',
                    'logout',
                ];

                $currentRouteName = $request->route()?->getName();

                if (!in_array($currentRouteName, $allowedRouteNames, true)) {
                    return redirect()->route('password.first_login.edit');
                }
            }
        }
        return $next($request);
    }
}
