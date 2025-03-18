<?php

use App\Models\Category;
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
            $table->string('part_number')->nullable();
            $table->string('upc_ean')->nullable();
            $table->json('title');
            $table->json('slug')->nullable();
            $table->json('sub_title')->nullable();
            $table->json('description');
            $table->json('features');
            $table->json('legal');
            $table->json('specifications');
            $table->json('technical_specifications');
            $table->decimal('price');
            $table->boolean('available')->default(true);
            $table->boolean('visible')->default(true);
            $table->foreignId('color_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->nullOnDelete();
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
