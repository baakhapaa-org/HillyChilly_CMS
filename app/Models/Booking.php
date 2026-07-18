<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'start_date', 'participants',
        'total_amount_npr', 'points_reward', 'status', 'payment_method',
        'payment_intent_id', 'payment_status',
    ];
    protected $casts = [
        'start_date'       => 'date',
        'participants'     => 'integer',
        'total_amount_npr' => 'integer',
        'points_reward'    => 'integer',
    ];
    public function user()              { return $this->belongsTo(User::class); }
    public function package()           { return $this->belongsTo(Package::class); }
    public function rewardTransaction() { return $this->hasOne(RewardTransaction::class); }
}
