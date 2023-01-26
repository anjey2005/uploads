<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    // открыть доступ к массовму присвоению
    protected $fillable = [
        'category_id',
        'user_id',
        'status',
        'title',
        'descr',
        'file',
        'file_name',
        'file_preview',
        'public'
    ];

    // значения по умолчанию
    protected $attributes = [
        'likes' => 0,
        'uploads' => 0,
        'status' => 1,
        'public' => true,
        'file_preview' => '',
    ];


    // привязка по ключу к модели User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // привязка по ключу к модели Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
