<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationNoteBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_note_banks', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->nullable();
            $table->string('user_id')->nullable();
            $table->enum('user_type', ['ADMIN','BANK'])->nullable();
            $table->text('note');
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
        Schema::dropIfExists('application_note_banks');
    }
}
