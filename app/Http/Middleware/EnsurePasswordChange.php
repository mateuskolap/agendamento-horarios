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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se o usuário deve alterar a senha e não está na rota de alteração de senha
        if ($user->must_change_password && !$request->routeIs(['password.change.edit', 'password.change.update'])) {
            // Armazena a URL pretendida para redirecionar após a alteração da senha
            if ($request->method() === 'GET' && !$request->expectsJson()) {
                session(['url.intended' => $request->url()]);
            }
            
            return redirect()->route('password.change.edit');
        }

        return $next($request);
    }
}
