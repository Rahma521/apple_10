<?php

use App\Models\City;
use App\Models\EducationLevel;
use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();;
            $table->foreignIdFor(EducationLevel::class)->constrained()->cascadeOnDelete();;
            $table->json('name');
            $table->string('domain');
            $table->float('delivery_price');
            $table->integer('max_order');
            $table->longText('address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
