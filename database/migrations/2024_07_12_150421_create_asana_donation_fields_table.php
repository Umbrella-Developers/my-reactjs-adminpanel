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
        Schema::create('asana_donation_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gid');
            $table->text('name')->nullable();
            $table->text('value')->nullable();
            $table->longText('field_object')->nullable();
            $table->string('field_type')->nullable();
            $table->integer('asana_donation_id');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asana_donation_fields');
    }
};
