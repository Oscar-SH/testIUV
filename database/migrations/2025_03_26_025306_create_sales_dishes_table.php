<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_dishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sale')->constrained(table: 'sales', indexName: 'sales_dishes_id_sale');
            $table->foreignId('id_dish')->constrained(table: 'dishes', indexName: 'sales_dishes_id_dish');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_dishes');
    }
};
