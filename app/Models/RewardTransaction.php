<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RewardTransaction extends Model
{
    protected $fillable = ['user_id', 'booking_id', 'points_delta', 'reason'];
    protected $casts    = ['points_delta' => 'integer'];
    public function user()    { return $this->belongsTo(User::class); }
    public function booking() { return $this->belongsTo(Booking::class); }
}
