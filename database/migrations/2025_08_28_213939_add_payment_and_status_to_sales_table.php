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
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'card', 'transfer'])
                ->default('cash')
                ->after('total'); // después del campo total
            $table->enum('status', ['paid', 'pending', 'canceled'])
                ->default('paid')
                ->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'status']);
        });
    }
};
