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
        Schema::table('rubrics', function (Blueprint $table) {
            $table->unsignedInteger('_lft')->nullable()->index();
            $table->unsignedInteger('_rgt')->nullable()->index();
            $table->unsignedInteger('depth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rubrics', function (Blueprint $table) {
            $table->dropColumn(['_lft', '_rgt', 'depth']);
        });
    }
};
