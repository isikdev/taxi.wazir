<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Driver;
use Symfony\Component\HttpFoundation\Response;

class CheckApplicationStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $driverId = session('driver_id');
        
        // Если нет ID водителя в сессии, пропускаем middleware
        if (!$driverId) {
            return $next($request);
        }
        
        $driver = Driver::find($driverId);
        
        // Если водитель не найден или анкета имеет статус draft, пропускаем
        if (!$driver || $driver->survey_status === 'draft') {
            return $next($request);
        }
        
        // Если анкета уже отправлена, перенаправляем на страницу статуса
        if (in_array($driver->survey_status, ['submitted', 'approved', 'rejected'])) {
            // Пропускаем проверку только для страницы статуса анкеты
            if ($request->routeIs('driver.survey.applicationStatus')) {
                return $next($request);
            }
            
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        return $next($request);
    }
} 