<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Photo extends Model {
 protected $fillable=['album_id','path','caption','sort_order'];
 public function album(): BelongsTo { return $this->belongsTo(Album::class); }
 public function getUrlAttribute(): string { return '/storage/'.ltrim($this->path,'/'); }
}
