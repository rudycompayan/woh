<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_member', function (Blueprint $table) {
            $table->increments('woh_member');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('bday')->nullable();
            $table->string('tree_position', 10)->nullable();
            $table->unsignedInteger('sponsor')->nullable();
            $table->unsignedInteger('downline_of')->nullable();
            $table->tinyInteger('left_leg_status')->default(0);
            $table->tinyInteger('right_leg_status')->default(0);
            $table->string('picture', 50)->nullable();
            $table->string('username', 20)->nullable();
            $table->string('password', 20)->nullable();
            $table->timestamps();
            $table->primary('woh_member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('woh_member');
    }
}
