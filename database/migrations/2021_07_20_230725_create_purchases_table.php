<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ore_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->date('date');
            $table->decimal('amount', 8, 2);
            $table->integer('quantity');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0);
            $table->decimal('remain', 8, 2);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('purchases');
    }
}
