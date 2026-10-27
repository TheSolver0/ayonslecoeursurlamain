<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PostPhoto extends Model {
 protected $fillable=['post_id','path','sort_order'];
 public function post(): BelongsTo { return $this->belongsTo(Post::class); }
 public function getUrlAttribute(): string { return '/storage/'.ltrim($this->path,'/'); }
}
