<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('description');
            $table->integer('fl_operation');
            $table->integer('fl_reversal');
            $table->decimal('value', 10, 2);
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();
        });

        // Chave estrangeira de usuários
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Chave estrangeira de movimentações originais (Utilizada para estornos)
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
