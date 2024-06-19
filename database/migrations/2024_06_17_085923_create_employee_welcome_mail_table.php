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
        Schema::create('employee_welcome_mail', function (Blueprint $table) {
            $table->id();
            $table->string("pan_number",10)->unique()->nullable(false);
            $table->foreign("pan_number")->references("pan_number")->on("employees");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_mail');
    }
};
