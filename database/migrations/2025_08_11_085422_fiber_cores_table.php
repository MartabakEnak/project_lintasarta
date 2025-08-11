<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fiber_cores', function (Blueprint $table) {
            $table->id();
            $table->string('nama_site');
            $table->string('region');
            $table->integer('tube_number');
            $table->integer('core');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('penggunaan', ['OK', 'NOK', 'Idle'])->default('OK');
            $table->integer('otdr');
            $table->string('source_site');
            $table->string('destination_site');
            $table->text('keterangan')->nullable();
            $table->string('tube')->nullable(); // Computed field like "TUBE 1"
            $table->timestamps();

            // Indexes for better performance
            $table->index(['region', 'status']);
            $table->index(['tube_number', 'core']);
            $table->unique(['nama_site', 'tube_number', 'core']); // Prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiber_cores');
    }
};