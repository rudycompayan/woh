<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_gc', function (Blueprint $table) {
            $table->increments('woh_gc');
            $table->string('gc_to', 50)->nullable();
            $table->string('gc_from', 50)->default('Windows Of Heaven Hypermart');
            $table->decimal('gc_amount', 11,2)->nullable();
            $table->string('gc_desc', 1000)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->dateTime('date_created')->nullable();
            $table->unsignedInteger('woh_member')->nullable();
            $table->primary('woh_gc');

            Schema::table('woh_gc', function (Blueprint $table) {
                $table->foreign('woh_member')
                    ->references('woh_member')
                    ->on('woh_member');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('woh_gc', function (Blueprint $table) {
            $table->dropForeign('woh_gc_woh_member_foreign');
        });

        Schema::drop('woh_gc');
    }
}
