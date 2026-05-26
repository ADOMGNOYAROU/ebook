<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->string('billing_cycle'); // monthly, yearly
            $table->json('features');
            $table->integer('max_ebooks')->default(10);
            $table->integer('max_users')->default(1);
            $table->integer('storage_mb')->default(100);
            $table->boolean('has_custom_domain')->default(false);
            $table->boolean('has_api')->default(false);
            $table->boolean('has_analytics')->default(false);
            $table->boolean('has_mobile_app')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
            
            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
