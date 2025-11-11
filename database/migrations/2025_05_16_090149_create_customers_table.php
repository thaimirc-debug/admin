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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->json('phones')->nullable(); // เก็บหลายเบอร์โทรในรูปแบบ JSON
            $table->integer('packet')->nullable();
            $table->date('start_date')->nullable();
            $table->string('job_description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
