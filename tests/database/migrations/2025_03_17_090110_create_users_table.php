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
            $table->string('name');
            $table->string('email')->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('lang')->default('en');
            $table->integer('type')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('code')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('education_level_id')->nullable();
            $table->unsignedBigInteger('instructor_type_id')->nullable();
            
            $table->foreign('city_id', 'users_city_id_foreign')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('education_level_id', 'users_education_level_id_foreign')->references('id')->on('education_levels')->onDelete('cascade');
            $table->foreign('instructor_type_id', 'users_instructor_type_id_foreign')->references('id')->on('instructor_types')->onDelete('cascade');
            $table->foreign('organization_id', 'users_organization_id_foreign')->references('id')->on('organizations')->onDelete('cascade');
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
