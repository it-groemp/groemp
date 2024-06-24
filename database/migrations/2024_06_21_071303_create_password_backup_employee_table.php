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
        Schema::create("password_backup_employee", function (Blueprint $table) {
            $table->id();
            $table->string("pan",10)->nullable(false);
            $table->foreign("pan")->references("pan_number")->on("employees");
            $table->string("password",80)->nullable(false);
            $table->timestamps()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("password_backup");
    }
};
