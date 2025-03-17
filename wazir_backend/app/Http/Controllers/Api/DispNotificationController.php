<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DispNotificationController extends Controller
{
    /**
     * Получить непрочитанные уведомления для диспетчера
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnread()
    {
        // Получаем глобальные уведомления (без привязки к пользователю)
        $notifications = Notification::whereNull('user_id')
                                    ->whereNull('read_at')
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
                                    
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Получить все уведомления для диспетчера
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        // Получаем глобальные уведомления (без привязки к пользователю)
        $notifications = Notification::whereNull('user_id')
                                    ->orderBy('created_at', 'desc')
                                    ->take(50)
                                    ->get();
                                    
        return response()->json([
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Отметить одно уведомление как прочитанное
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = Carbon::now();
        $notification->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Отметить все уведомления как прочитанные
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        // Отмечаем как прочитанные все глобальные уведомления
        Notification::whereNull('user_id')
                    ->whereNull('read_at')
                    ->update(['read_at' => Carbon::now()]);
                  
        return response()->json(['success' => true]);
    }
} 