<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('confession_id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->index('confession_id', 'likes_confession_id_lookup_index');
            $table->dropUnique(['confession_id', 'ip_hash']);
            $table->string('ip_hash', 64)->nullable()->change();
            $table->unique(['confession_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropUnique(['confession_id', 'user_id']);
            $table->dropIndex('likes_confession_id_lookup_index');
            $table->string('ip_hash', 64)->nullable(false)->change();
            $table->unique(['confession_id', 'ip_hash']);

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
