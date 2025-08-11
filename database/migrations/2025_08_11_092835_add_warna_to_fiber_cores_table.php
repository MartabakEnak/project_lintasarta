<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fiber_cores', function (Blueprint $table) {
            $table->string('warna')->nullable();
        });
    }

    public function down()
    {
        Schema::table('fiber_cores', function (Blueprint $table) {
            $table->dropColumn('warna');
        });
    }
};
