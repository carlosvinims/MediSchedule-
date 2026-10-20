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
        Schema::create('commitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('appointment_date');
            $table->string('status');
            $table->longText('notes');
            $table->date('canceled_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commitments');
    }
};
