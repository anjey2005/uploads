<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // открыть доступ к массовму присвоению
    protected $fillable = ['name'];

    // привязка по ключу к модели Upload
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
}
