<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_transaction_type', function (Blueprint $table) {
            $table->increments('woh_transaction_type');
            $table->string('transaction_type', 50)->nullable();
            $table->primary('woh_transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('woh_transaction_type');
    }
}
