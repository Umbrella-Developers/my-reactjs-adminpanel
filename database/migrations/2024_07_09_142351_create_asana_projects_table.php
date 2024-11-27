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
        Schema::create('asana_projects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gid');
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('workspace_gid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asana_projects');
    }
};
