<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE INDEX IF NOT EXISTS news_fulltext_idx
            ON news
            USING GIN (
                to_tsvector('english', coalesce(title,'') || ' ' || coalesce(body,''))
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS news_fulltext_idx;");
    }
};
