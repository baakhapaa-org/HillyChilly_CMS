<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'image_url', 'content', 'excerpt',
        'category', 'is_published', 'view_count', 'meta_title', 'meta_description',
    ];
    protected $casts = ['is_published' => 'boolean', 'view_count' => 'integer'];
    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
