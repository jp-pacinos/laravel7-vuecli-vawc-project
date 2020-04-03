<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVawcUserRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vawc_user_records', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable()->default('WITNESS');
            $table->tinyInteger('position_status')->default(3);

            $table->unsignedBigInteger('record_id');
            $table->foreign('record_id')->references('id')->on('vawc_records')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('vawc_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vawc_user_record');
    }
}
