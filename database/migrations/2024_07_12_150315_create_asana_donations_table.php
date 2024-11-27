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
        Schema::create('asana_donations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gid');
            $table->string('title')->nullable();
            $table->bigInteger('hit_job_id')->nullable();
            $table->integer('asana_project_id');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asana_donations');
    }
};
