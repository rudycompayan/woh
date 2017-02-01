<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woh_member_transactions', function (Blueprint $table) {
            $table->increments('woh_member_transactions');
            $table->unsignedInteger('woh_member')->nullable();
            $table->unsignedInteger('woh_transaction_type')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->primary('woh_member_transactions');
        });

        Schema::table('woh_member_transactions', function (Blueprint $table) {
            $table->foreign('woh_member')
                ->references('woh_member')
                ->on('woh_member');
        });

        Schema::table('woh_member_transactions', function (Blueprint $table) {
            $table->foreign('woh_transaction_type')
                ->references('woh_transaction_type')
                ->on('woh_transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('woh_member_transactions', function (Blueprint $table) {
            $table->dropForeign('woh_member_transactions_woh_member_foreign');
        });

        Schema::table('woh_member_transactions', function (Blueprint $table) {
            $table->dropForeign('woh_member_transactions_woh_transaction_type_foreign');
        });

        Schema::drop('woh_member_transactions');
    }
}
