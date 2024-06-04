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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->float('value');
            $table->float('min_value')->nullable();
            $table->float('max_value')->nullable();
            $table->string('promo')->unique();
            $table->dateTime('expires_at');
            $table->text('desc')->nullable();
            $table->enum('type', ['fixed', 'percentage']);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
