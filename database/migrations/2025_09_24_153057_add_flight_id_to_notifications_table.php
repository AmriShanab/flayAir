<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('flight_id')->nullable()->after('worker_id');

            $table->foreign('flight_id')
                ->references('id')->on('flights')
                ->onDelete('cascade'); // delete notifications if flight is deleted
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['flight_id']);
            $table->dropColumn('flight_id');
        });
    }
};
