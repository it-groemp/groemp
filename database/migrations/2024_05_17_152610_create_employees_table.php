<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("pan_number",10)->unique()->nullable(false);
            $table->string("employee_code",20)->nullable(false);
            $table->string("name",50)->nullable(false);
            $table->string("mobile",10)->nullable(false);
            $table->string("email",100)->nullable(false);
            $table->string("designation",50)->nullable(false);
            $table->string("company",10)->nullable(false);
            $table->foreign("company")->references("pan")->on("companies");
            $table->integer("benefit_amount")->autoincrement(false)->nullable(false);
            $table->timestamp("from_date")->useCurrent();
            $table->timestamp("to_date")->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
