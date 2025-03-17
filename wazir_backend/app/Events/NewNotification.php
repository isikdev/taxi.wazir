<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Если уведомление привязано к пользователю, отправляем в его приватный канал
        if ($this->notification->user_id) {
            return new PrivateChannel('user.' . $this->notification->user_id);
        }
        
        // Иначе отправляем в общий канал уведомлений
        return new Channel('notifications');
    }
    
    /**
     * Получить данные для трансляции.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'notification' => [
                'id' => $this->notification->id,
                'type' => $this->notification->type,
                'title' => $this->notification->title,
                'data' => $this->notification->data,
                'created_at' => $this->notification->created_at
            ]
        ];
    }
}
