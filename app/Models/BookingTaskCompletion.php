<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTaskCompletion extends Model
{
    protected $fillable = ['booking_id', 'task_id', 'proof_path', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function task()    { return $this->belongsTo(PackageTask::class, 'task_id'); }
}
