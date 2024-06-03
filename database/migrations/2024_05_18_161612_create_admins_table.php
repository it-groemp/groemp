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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string("name",50)->nullable(false);
            $table->string("mobile",10)->nullable(false);
            $table->string("email",100)->nullable(false)->unique();
            $table->string("company",10)->nullable(false);
            $table->string("password",80)->default("")->nullable(false);
            $table->string("role",10)->nullable(false)->default("Employer");
            $table->timestamp("from_date")->useCurrent();
            $table->timestamp("to_date")->nullable(true);
            $table->string("created_by",100)->nullable(false);
            $table->string("updated_by",100)->nullable(false);
            $table->timestamp("created_at")->nullable(false)->useCurrent();
            $table->timestamp("updated_at")->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
