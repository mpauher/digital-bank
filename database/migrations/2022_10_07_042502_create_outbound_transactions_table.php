<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutboundTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outbound_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('send_user_id')->constrained('users')->ondelete('cascade')->onupdate('cascade');
            $table->foreignId('receive_user_id')->constrained('users')->ondelete('cascade')->onupdate('cascade');
            $table->double('outbound_amount');
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
        Schema::dropIfExists('outbound_transactions');
    }
}
