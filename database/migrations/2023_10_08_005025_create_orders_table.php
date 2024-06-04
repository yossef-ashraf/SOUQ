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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('shipping_id')->constrained();
            $table->foreignId('address_id')->constrained();

            $table->float('discount')->default(0);
            $table->float('sub_total');
            $table->float('total_price');
            $table->string('payment_type')->default('cash');
            $table->string('promo')->nullable();
            $table->string('driver_by')->nullable();
            $table->enum('status', ['Pending', 'Processing', 'Shipping','Delivered','Cancelled','Returned']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
