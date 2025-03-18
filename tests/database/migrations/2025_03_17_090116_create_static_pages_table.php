<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('hero_img')->nullable();
            $table->json('hero_main_title')->nullable();
            $table->json('hero_title')->nullable();
            $table->json('hero_desc')->nullable();
            $table->json('sec_title')->nullable();
            $table->json('sec_desc')->nullable();
            $table->json('third_title')->nullable();
            $table->json('third_desc')->nullable();
            $table->json('end_title')->nullable();
            $table->json('end_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('static_pages');
    }
}
