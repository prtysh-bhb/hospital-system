<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_leaves', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('approval_type', ['auto', 'admin', 'frontdesk'])->default('admin');
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('leave_type', ['full_day', 'half_day', 'custom']);
            $table->enum('half_day_slot', ['morning', 'evening'])->nullable();
            $table->enum('start_date_type', ['full_day', 'half_day'])->nullable();
            $table->enum('start_half_slot', ['morning', 'evening'])->nullable();
            $table->enum('end_date_type', ['full_day', 'half_day'])->nullable();
            $table->enum('end_half_slot', ['morning', 'evening'])->nullable();
            $table->boolean('is_adhoc')->default(false);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                ->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['doctor_id', 'start_date', 'end_date']);
            $table->index('status');
            $table->index('is_adhoc');
            $table->index('leave_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_leaves');
    }
};
