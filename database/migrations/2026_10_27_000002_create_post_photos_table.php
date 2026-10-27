<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('post_photos',function(Blueprint $t){$t->id();$t->foreignId('post_id')->constrained()->cascadeOnDelete();$t->string('path');$t->unsignedInteger('sort_order')->default(0);$t->timestamps();}); }
 public function down(): void { Schema::dropIfExists('post_photos'); }
};
