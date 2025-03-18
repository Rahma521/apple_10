<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('brief')->nullable();
            $table->json('desc')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('status')->default(1);
            $table->integer('type');
            $table->integer('bundle_type')->nullable();
            $table->decimal('percent', 8, 2)->default(0.00);
            $table->double('price_before_discount')->default(0);
            $table->double('price_after_discount')->default(0);
            $table->unsignedBigInteger('organization_id');
            $table->timestamps();
            
            $table->foreign('organization_id', 'offers_organization_id_foreign')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
