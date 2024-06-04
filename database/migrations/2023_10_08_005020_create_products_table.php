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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description');
            $table->string('material');
            $table->string('img');
            $table->integer('discount')->nullable();
            $table->boolean('has_discount_category')->default(true);
            $table->boolean('has_discount_brand')->default(true);
            $table->integer('like_num')->nullable();
            $table->integer('rate_average')->nullable();
            $table->integer('rate_num')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
