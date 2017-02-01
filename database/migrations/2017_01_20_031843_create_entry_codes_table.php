<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_entry_codes', function (Blueprint $table) {
            $table->increments('woh_entry_codes');
            $table->string('code', 10)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->primary('woh_entry_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('woh_entry_codes');
    }
}
