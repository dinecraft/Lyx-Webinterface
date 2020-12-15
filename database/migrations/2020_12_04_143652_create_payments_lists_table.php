<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentslistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentslists', function (Blueprint $table) {
            $table->id();
            $table->decimal("price", 10, 2)->nullable();
            $table->string("user")->nullable();
            $table->string("payID")->nullable();
            $table->string("status")->nullable();
            $table->string("extra")->nullable();
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
        Schema::dropIfExists('paymentsLists');
    }
}
