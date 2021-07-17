<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('is_full_time')->default(1);
            $table->string('nickname')->index()->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('social_status')->nullable();
            $table->string('national_id')->nullable();
            $table->integer('salary')->nullable();
            $table->date('worked_date')->nullable();
            $table->text('details')->nullable();
            $table->string('avatar')->nullable();
            $table->foreignId('category_id')->constrained('employees_categories');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
