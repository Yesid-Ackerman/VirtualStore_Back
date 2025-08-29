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
         Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // usuario que hizo la acción
            $table->string('action'); // Ej: "create_product", "update_sale"
            $table->string('entity_type'); // Ej: "Product", "Sale"
            $table->unsignedBigInteger('entity_id'); // ID del producto, venta, etc.
            $table->text('changes')->nullable(); // JSON con los cambios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
