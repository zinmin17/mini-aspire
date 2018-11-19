<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loan_id');
            $table->integer('payment_no');
            $table->float('repayable_amount', 8, 2);
            $table->float('paid_amount', 8, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->date('due_date');
            $table->float('monthly_interest_rate', 8, 2);
            $table->string('payment_status')->default("unpaid"); // "unpaid" "paid"
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
        Schema::dropIfExists('repayment');
    }
}
