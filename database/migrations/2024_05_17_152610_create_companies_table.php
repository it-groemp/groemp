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
        Schema::create('companies', function (Blueprint $table) {
            $table->string("pan")->nullable(false);
            $table->string("name",255)->nullable(false);
            $table->string("group_company_code",255)->default("");
            $table->string("mobile","10")->nullable(false);
            $table->string("email","50");
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
        Schema::dropIfExists('companies');
    }
};
