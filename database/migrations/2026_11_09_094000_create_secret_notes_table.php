<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('secret_notes', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('recipient_name')->nullable();
            $table->string('creator_name')->nullable();
            $table->string('creator_hint')->nullable();
            $table->string('room_context')->nullable()->default('Konsinyering Room');
            $table->enum('status', ['created', 'opened', 'single', 'taken', 'submitted', 'destroyed'])->default('created');
            $table->string('relationship_choice')->nullable();
            $table->string('contact_type')->nullable(); // instagram, whatsapp, telegram
            $table->string('contact_value')->nullable();
            $table->text('note_from_recipient')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->boolean('is_destroyed')->default(false);
            $table->timestamp('destroyed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secret_notes');
    }
};

