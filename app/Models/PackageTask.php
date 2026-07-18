<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageTask extends Model
{
    protected $fillable = ['package_id', 'type', 'title', 'points', 'config', 'sort_order'];
    protected $casts    = ['config' => 'array', 'points' => 'integer', 'sort_order' => 'integer'];
    public function package() { return $this->belongsTo(Package::class); }
}
