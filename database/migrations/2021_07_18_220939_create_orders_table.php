<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('address')->nullable();
            $table->decimal('amount_before_discount', 10, 2);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('amount_after_discount', 10, 2);
            $table->decimal('remain', 8, 2);
            $table->tinyInteger('status')->default(1);
            $table->foreignId('client_id')->constrained();
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
        Schema::dropIfExists('orders');
    }
}
