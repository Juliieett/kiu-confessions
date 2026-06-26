<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('confession_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confession_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['confession_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confession_tag');
    }
};
