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
        Schema::table('orders',function(Blueprint $table){
            $table->enum('payment_status',['paid','not paid'])->after('grand_total')->default('not paid');
            $table->enum('status',['pendding','shipped','delivered'])->after('payment_status')->default('pendding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders',function(Blueprint $table){
            $table->dropColomn('payment_status');
            $table->dropColumn('status');
        });
    }
};
