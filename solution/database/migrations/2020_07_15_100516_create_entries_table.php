<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            // Columns
            $table->id();
            $table->foreignId('user_id');
            $table->string('label');
            $table->bigInteger('amount_cents');
            $table->dateTime('date_time', 0);
            $table->timestamps();

            // Indices
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('date_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
