<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->enum('status_order', ['Pending', 'Processing', 'Shipping','Delivered', 'Cancelled']);
            $table->float('total_price',8,2);
            $table->string('streetAddress');
            $table->string('city');
            $table->string('state');
            $table->string('specialMark');
            $table->string('paymentMethod');

            $table->string('discountcode')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_id');
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
