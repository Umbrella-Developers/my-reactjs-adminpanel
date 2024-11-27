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
        Schema::table('asana_donation_fields', function (Blueprint $table) {
            $table->bigInteger('asana_donation_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asana_donation_fields', function (Blueprint $table) {
            $table->integer('asana_donation_id')->change();
        });
    }
};
