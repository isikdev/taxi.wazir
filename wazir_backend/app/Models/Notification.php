<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'type', 'title', 'data', 'read_at'
    ];
    
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];
    
    /**
     * Получить пользователя, которому принадлежит уведомление
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Проверить, прочитано ли уведомление
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }
}
