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
        Schema::create('employee_availed_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id")->nullable(false);
            $table->foreign("employee_id")->references("id")->on("employees");
            $table->timestamp("date_availed")->nullable(false);
            $table->unsignedBigInteger("benefit_id")->nullable(false);
            $table->foreign("benefit_id")->references("id")->on("benefits");
            $table->unsignedBigInteger("category_id")->nullable(false);
            $table->foreign("category_id")->references("id")->on("categories");
            $table->integer("price")->nullable(true);
            $table->integer("quantity")->nullable(true);
            $table->string("description",10000)->nullable(true);
            $table->timestamp("created_at")->nullable(false)->useCurrent();
            $table->timestamp("updated_at")->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_availed_benefits');
    }
};
