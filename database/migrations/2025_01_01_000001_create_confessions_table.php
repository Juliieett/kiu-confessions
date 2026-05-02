<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create confessions table.
 *
 * Contains all fields required by the exam rubric:
 *   title, description, status (pending/done), deadline (date)
 * Plus extras for the KIU Confessions theme:
 *   category, ip_hash (for spam prevention)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('confessions', function (Blueprint $table) {
            $table->id();

            // Required by exam rubric ─────────────────────────────────────────
            $table->string('title', 150);               // Short subject of the confession
            $table->text('description');                 // The confession body text
            $table->enum('status', [                    // Moderation status
                'pending',
                'approved',
                'rejected',
            ])->default('pending');
            $table->date('deadline')->nullable();        // Admin review-by date

            // Extra fields for the confessions theme ──────────────────────────
            $table->string('category', 50)->default('Other'); // e.g. Study Life, Crush
            $table->string('ip_hash', 64)->nullable();         // Hashed IP for spam guard

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confessions');
    }
};
