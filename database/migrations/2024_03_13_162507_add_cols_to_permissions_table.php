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
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('temp_permission_id')->nullable();
            $table->text('action')->nullable();
            $table->enum('type', ['pre-define', 'user-define'])->default('user-define'); // For MySQL 8.0 use string('guard_name', 125);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('action');
            $table->dropColumn('temp_permission_id');
            $table->dropColumn('type');
        });
    }
};
