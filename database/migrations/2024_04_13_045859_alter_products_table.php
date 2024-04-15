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
        Schema::table('products',function(Blueprint $table){
            $table->text('short_description')->nullable()->after('description');
            $table->text('shipping_returns')->nullable()->after('short_description');
            $table->text('related_products')->nullable()->after('shipping_returns');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products',function(Blueprint $table){
            $table->dropColomn('short_description');
            $table->dropColomn('shipping_returns');
            $table->dropColomn('related_products');

        });
    }
};
