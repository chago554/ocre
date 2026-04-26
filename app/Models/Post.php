<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url',
        'read_time',
        'category',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'category'     => 'array',
            'is_published' => 'boolean',
        ];
    }
}
