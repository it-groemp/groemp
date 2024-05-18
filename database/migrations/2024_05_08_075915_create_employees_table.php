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
            $table->string("pan_number",10)->primary();
            $table->string("name",50)->null(false);
            $table->string("mobile","10")->null(false);
            $table->string("email","100")->null(false);
            $table->string("designation","50")->null(false);
            $table->string("company","50")->null(false);
            $table->integer("benefit_amount")->autoincrement(false)->null(false);
            $table->string("role",10)->null(false)->default("Employee");
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
