<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('google_id');
            $table->string('token');
            $table->string('refresh_token');
            $table->string('name');
            $table->string('email');
            $table->dateTime('expiry_time');
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
        Schema::dropIfExists('google_auths');
    }
}
