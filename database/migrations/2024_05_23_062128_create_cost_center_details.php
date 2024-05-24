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
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->string("company")->nullable(false);            
            $table->foreign("company")->references("pan")->on("companies");
            $table->string("cc1",20)->nullable(false);
            $table->string("cc2",20)->nullable(false);
            $table->string("cc3",20);
            $table->string("cc4",20);
            $table->string("cc5",20);
            $table->string("cc6",20);
            $table->string("cc7",20);
            $table->string("cc8",20);
            $table->string("cc9",20);
            $table->string("cc10",20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_center_details');
    }
};
