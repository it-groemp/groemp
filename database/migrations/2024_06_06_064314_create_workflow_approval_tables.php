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
        Schema::create("workflow_approval", function (Blueprint $table) {
            $table->id();
            $table->string("company",10)->nullable(false);
            $table->foreign("company")->references("pan")->on("companies");
            $table->string("type",10)->nullable(true);
            $table->string("approver_email",100)->nullable(false);
            $table->string("approval_for",50)->nullable(true);
            $table->string("token",100)->nullable(true);
            $table->string("created_by",100)->nullable(false);
            $table->timestamp("created_at")->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_approval_tables');
    }
};
