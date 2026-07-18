<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'category', 'duration_days',
        'price_npr', 'price_usd', 'points_reward', 'image_url',
        'location_lat', 'location_lng', 'location_label',
        'is_active', 'is_featured', 'is_free',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'is_free'      => 'boolean',
        'price_npr'    => 'integer',
        'points_reward'=> 'integer',
        'duration_days'=> 'integer',
        'location_lat' => 'float',
        'location_lng' => 'float',
    ];

    public function tasks()    { return $this->hasMany(PackageTask::class)->orderBy('sort_order'); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
}
