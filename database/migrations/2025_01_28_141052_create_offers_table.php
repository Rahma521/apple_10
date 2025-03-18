<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
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

            $table->decimal('percent')->default(0);
            $table->foreignIdFor(Organization::class)->constrained()->cascadeOnDelete();
            $table->float('price_before_discount')->default(0);
            $table->float('price_after_discount')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
