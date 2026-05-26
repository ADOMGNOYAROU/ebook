<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('subscription_type', ['free', 'premium'])->default('free')->after('role');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_type');
            $table->integer('downloads_remaining')->default(5)->after('subscription_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_type', 'subscription_ends_at', 'downloads_remaining']);
        });
    }
};
