<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requested_id')->nullable(); // nullable - sometime user may not request and admin create for user loan
            $table->integer('user_id');
            $table->float('amount', 8, 2);
            $table->float('interest_rate', 8, 2);
            $table->float('arrangement_fee', 8, 2)->default(0);
            $table->integer('duration');// 1-6
            $table->date('start_date');
            $table->string('status')->default("activated"); // 'activated' 'disabled'
            $table->string('user_respond')->default("pending"); //'pending' 'accepted' 'rejected'
            $table->integer('admin_id'); // loan approver id (admin)
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
        Schema::dropIfExists('loan');
    }
}
