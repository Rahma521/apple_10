<?php

use App\Models\InstructorType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignIdFor(InstructorType::class);
            $table->text('message')->nullable();
            $table->string('institution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_forms');
    }
};
