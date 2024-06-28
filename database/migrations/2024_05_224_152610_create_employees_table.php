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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("pan_number",10)->unique()->nullable(false);
            $table->string("employee_code",20)->nullable(false);
            $table->string("name",50)->nullable(false);
            $table->string("mobile",10)->nullable(false);
            $table->string("email",100)->nullable(false);
            $table->date("date_of_birth")->nullable(true);
            $table->string("designation",50)->nullable(false);
            $table->string("company",10)->nullable(false);
            $table->foreign("company")->references("pan")->on("companies");
            $table->string("password",80)->default("")->nullable(false);
            $table->string("photo",50)->nullable(true);
            $table->enum("marital_status",["Single","Married","Widow","Divorced"])->default("Single");
            $table->integer("num_of_kids")->autoincrement(false)->default(0);
            $table->string("approver1",100)->nullable(true);
            $table->string("approver2",100)->nullable(true);
            $table->string("approver3",100)->nullable(true);
            $table->string("verified",3)->nullable(false)->default("No");
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
        Schema::dropIfExists('employees');
    }
};
