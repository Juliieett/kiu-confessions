<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->foreignId('referenced_confession_id')
                ->nullable()
                ->after('category_id')
                ->constrained('confessions')
                ->nullOnDelete();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confession_id')->constrained()->cascadeOnDelete();
            $table->string('ip_hash', 64);
            $table->timestamps();

            $table->unique(['confession_id', 'ip_hash']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confession_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('likes');

        Schema::table('confessions', function (Blueprint $table) {
            $table->dropForeign(['referenced_confession_id']);
            $table->dropColumn('referenced_confession_id');
        });
    }
};
