<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueRegionTubeNumberCoreStatusOnFiberCores extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fiber_cores', function (Blueprint $table) {
            $table->dropUnique('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fiber_cores', callback: function (Blueprint $table) {
            $table->unique('region');
        });
    }
};
