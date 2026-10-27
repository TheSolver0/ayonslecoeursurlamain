<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('users',function(Blueprint $t){ $t->id();$t->string('name');$t->string('email')->unique();$t->string('password');$t->boolean('is_admin')->default(false);$t->rememberToken();$t->timestamps(); });
  Schema::create('posts',function(Blueprint $t){ $t->id();$t->string('title',180);$t->string('slug',200)->unique();$t->text('excerpt')->nullable();$t->longText('body');$t->string('cover_path')->nullable();$t->string('status',20)->default('draft');$t->timestamp('published_at')->nullable();$t->timestamps();$t->index(['status','published_at']); });
  Schema::create('albums',function(Blueprint $t){ $t->id();$t->string('title',180);$t->string('slug',200)->unique();$t->text('description')->nullable();$t->date('event_date')->nullable();$t->boolean('is_published')->default(false);$t->timestamps(); });
  Schema::create('photos',function(Blueprint $t){ $t->id();$t->foreignId('album_id')->constrained()->cascadeOnDelete();$t->string('path');$t->string('caption')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->timestamps(); });
  Schema::create('settings',function(Blueprint $t){ $t->id();$t->json('values');$t->timestamps(); });
 }
 public function down(): void { foreach(['settings','photos','albums','posts','users'] as $table){ Schema::dropIfExists($table); } }
};
