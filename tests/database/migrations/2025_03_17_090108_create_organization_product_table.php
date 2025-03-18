<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('organization_id');
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->timestamps();
            
            $table->primary(['product_id', 'organization_id']);
            $table->foreign('organization_id', 'organization_product_organization_id_foreign')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('product_id', 'organization_product_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_product');
    }
}
