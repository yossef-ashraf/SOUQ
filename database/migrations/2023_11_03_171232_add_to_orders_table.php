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
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->enum('status', ['UnConfirmed','Confirmed', 'Pending', 'Processing', 'Shipping','Delivered','Cancelled','Returned']);
            $table->string('pay_id')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn('status');
            $table->dropColumn('pay_id');
            $table->dropColumn('phone');
        });
    }
};
