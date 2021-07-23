<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2);
            $table->tinyInteger('with')->default(1);
            $table->decimal('with_value', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2)->default(0);
            $table->date('date');
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
