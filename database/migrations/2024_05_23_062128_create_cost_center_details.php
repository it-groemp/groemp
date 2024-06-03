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
            $table->string("cc3",20)->nullable(true);
            $table->string("cc4",20)->nullable(true);
            $table->string("cc5",20)->nullable(true);
            $table->string("cc6",20)->nullable(true);
            $table->string("cc7",20)->nullable(true);
            $table->string("cc8",20)->nullable(true);
            $table->string("cc9",20)->nullable(true);
            $table->string("cc10",20)->nullable(true);
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
        Schema::dropIfExists('cost_center_details');
    }
};
