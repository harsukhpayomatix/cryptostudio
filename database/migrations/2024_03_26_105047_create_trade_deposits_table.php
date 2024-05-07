<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('deposit_type')->nullable();
            $table->string('account_id')->nullable();
            $table->string('currency_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('sender_wallet_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('swift')->nullable();
            $table->string('transfer_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('note')->nullable();
            $table->enum('confirm_by_user', ['0', '1'])->default('0');
            $table->enum('confirm_by_admin', ['0', '1'])->default('0');
            $table->softDeletes();
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
        Schema::dropIfExists('trade_deposits');
    }
}
