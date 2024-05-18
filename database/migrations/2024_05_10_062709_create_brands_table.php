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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string("name",50)->null(false);
            $table->unsignedBigInteger("benefit_id")->nullable(false);
            $table->foreign("benefit_id")->references("id")->on("benefits");
            $table->string("image_name",60)->null(false);
            $table->string("created_by")->nullable(false);
            $table->string("updated_by")->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand');
    }
};
