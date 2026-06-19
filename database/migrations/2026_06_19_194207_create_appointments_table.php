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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
             $table->string('userId');
            $table->string('docId');
            $table->string('slotDate');
            $table->string('slotTime');
            $table->boolean('cancelled')->default(false);
            $table->boolean('payment')->default(false);
            $table->boolean('isCompleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
