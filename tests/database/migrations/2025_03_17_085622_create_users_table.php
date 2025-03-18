<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('lang')->default('en');
            $table->integer('type')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('education_level_id')->nullable();
            $table->unsignedBigInteger('instructor_type_id')->nullable();
            $table->string('code')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('city_id', 'users_city_id_foreign')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
