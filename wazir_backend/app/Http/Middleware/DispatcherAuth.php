<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DispatcherAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли диспетчер
        if (!session()->has('dispatcher_auth') || session('dispatcher_auth') !== true) {
            // Если запрос ожидает JSON ответ (например, API запрос),
            // возвращаем JSON ответ с ошибкой
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            // Если обычный запрос - перенаправляем на страницу входа
            return redirect()->route('dispatcher.login');
        }
        
        return $next($request);
    }
} 