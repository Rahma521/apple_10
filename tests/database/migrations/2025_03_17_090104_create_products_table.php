<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('slug')->nullable();
            $table->json('sub_title')->nullable();
            $table->json('description');
            $table->json('features');
            $table->json('legal');
            $table->json('specifications');
            $table->json('technical_specifications');
            $table->decimal('price', 8, 2);
            $table->boolean('available')->default(1);
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->timestamps();
            
            $table->foreign('color_id', 'products_color_id_foreign')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('sub_category_id', 'products_sub_category_id_foreign')->references('id')->on('categories')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
