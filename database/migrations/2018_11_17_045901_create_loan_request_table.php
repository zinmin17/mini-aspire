<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('amount');
            $table->string('duration');
            $table->string('status')->default("pending"); // 'pending' 'approved' 'rejected'
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
        Schema::dropIfExists('loan_request');
    }
}
