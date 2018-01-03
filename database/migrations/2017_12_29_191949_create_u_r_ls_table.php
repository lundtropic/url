<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateURLsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_r_ls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('collection_id');
            $table->string('short_url');
            $table->string('long_url');
            $table->unsignedInteger('all_time')->default(0);
            $table->unsignedInteger('month')->default(0);
            $table->unsignedInteger('week')->default(0);
            $table->unsignedInteger('day')->default(0);
            $table->unsignedInteger('two_hours')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_r_ls');
    }
}
