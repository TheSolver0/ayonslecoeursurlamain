<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Album extends Model {
 protected $fillable=['title','slug','description','event_date','is_published'];
 protected function casts(): array { return ['event_date'=>'date','is_published'=>'boolean']; }
 public function photos(): HasMany { return $this->hasMany(Photo::class)->orderBy('sort_order')->orderBy('id'); }
 public function scopePublished(Builder $q): Builder { return $q->where('is_published',true); }
 public function getCoverUrlAttribute(): string { return $this->photos->first()?->url ?? asset('assets/1.webp'); }
}
