<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'designation', 'avatar_url', 'package_name', 'content', 'rating', 'is_visible', 'sort_order'];
    protected $casts    = ['rating' => 'integer', 'is_visible' => 'boolean', 'sort_order' => 'integer'];
}
