<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Получить непрочитанные уведомления
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnread()
    {
        $notifications = Notification::whereNull('read_at')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                                    
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Получить все уведомления
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
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
        $notification->read_at = now();
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
        Notification::whereNull('read_at')
                  ->update(['read_at' => now()]);
                  
        return response()->json(['success' => true]);
    }
    
    /**
     * Создать новое уведомление
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @return \App\Models\Notification
     */
    public function create($type, $title, $message, $data = [])
    {
        $data = array_merge(['message' => $message], $data);
        
        $notification = Notification::create([
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'read_at' => null
        ]);
        
        try {
            // Вызываем событие для WebSockets немедленно
            broadcast(new NewNotification($notification))->toOthers();
            
            // Для отладки
            \Log::info('Отправлено уведомление через broadcast', [
                'notification_id' => $notification->id,
                'type' => $type,
                'title' => $title
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при отправке уведомления через broadcast', [
                'error' => $e->getMessage(),
                'notification_id' => $notification->id
            ]);
        }
        
        return $notification;
    }
}