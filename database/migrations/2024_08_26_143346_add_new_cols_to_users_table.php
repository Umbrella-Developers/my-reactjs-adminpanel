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
            // Adding new columns
            $table->string('last_name')->nullable();
            $table->string('picture')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping newly added columns
            $table->dropColumn('last_name');
            $table->dropColumn('picture');
            $table->dropColumn('company_name');
            $table->dropColumn('company_website');
            $table->dropColumn('company_address');
        });
    }
};
