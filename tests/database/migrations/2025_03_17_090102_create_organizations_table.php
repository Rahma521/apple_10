<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('education_level_id');
            $table->json('name');
            $table->string('domain');
            $table->double('delivery_price');
            $table->integer('max_order');
            $table->longText('address');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            
            $table->foreign('city_id', 'organizations_city_id_foreign')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('education_level_id', 'organizations_education_level_id_foreign')->references('id')->on('education_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
