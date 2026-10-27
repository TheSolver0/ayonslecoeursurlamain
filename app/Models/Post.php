<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Post extends Model {
 protected $fillable=['title','slug','excerpt','body','cover_path','status','published_at'];
 protected function casts(): array { return ['published_at'=>'datetime']; }
 public function scopePublished(Builder $q): Builder { return $q->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now()); }
 public function photos(): HasMany { return $this->hasMany(PostPhoto::class)->orderBy('sort_order')->orderBy('id'); }
 public function getCoverUrlAttribute(): string { return $this->cover_path ? '/storage/'.ltrim($this->cover_path,'/') : asset('assets/1.webp'); }
 public function getHtmlAttribute(): string { return Str::markdown($this->body,['html_input'=>'strip','allow_unsafe_links'=>false]); }
}
