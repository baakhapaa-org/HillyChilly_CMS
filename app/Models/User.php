<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'avatar_url', 'points_balance', 'is_admin'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'points_balance'    => 'integer',
            'is_admin'          => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool { return $this->is_admin; }

    public function bookings()           { return $this->hasMany(Booking::class); }
    public function rewardTransactions() { return $this->hasMany(RewardTransaction::class); }
    public function badges()             { return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at')->withTimestamps(); }
}
