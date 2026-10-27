<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {
 protected $fillable=['values'];
 protected function casts(): array { return ['values'=>'array']; }
 public static function current(): array { return array_replace(config('association'),static::find(1)?->values ?? []); }
}
