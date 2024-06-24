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
        Schema::create("password_backup_admin", function (Blueprint $table) {
            $table->id();
            $table->string("pan",10)->nullable(false);
            $table->foreign("pan")->references("pan")->on("admins");
            $table->string("password",80)->nullable(false);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
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
