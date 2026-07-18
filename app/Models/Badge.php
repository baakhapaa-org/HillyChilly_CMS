<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'icon_url', 'category', 'required_points'];
    protected $casts    = ['required_points' => 'integer'];
    public function users() { return $this->belongsToMany(User::class, 'user_badges')->withPivot('earned_at')->withTimestamps(); }
}
