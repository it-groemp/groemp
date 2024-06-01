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
        Schema::create('employee_benefits', function (Blueprint $table) {
            $table->id();
            $table->string("pan_number",10)->unique(true)->nullable(false);
            $table->foreign("pan_number")->references("pan_number")->on("employees");
            $table->integer("current_benefit")->nullable(false)->default(0);
            $table->integer("previous_benefit")->nullable(false)->default(0);
            $table->integer("availed_benefit")->nullable(false)->default(0);
            $table->string("created_by",100)->nullable(false);
            $table->string("updated_by",100)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
