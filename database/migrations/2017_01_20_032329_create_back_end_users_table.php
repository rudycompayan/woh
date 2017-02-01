<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackEndUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_users', function (Blueprint $table) {
            $table->increments('woh_users');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('user_type', 60);
            $table->rememberToken();
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
        Schema::drop('woh_users');
    }
}
