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
        Schema::table('orders', function (Blueprint $table) {
            // This creates a `user_id` column and sets it as a foreign key
            // referencing the `id` column on the `users` table.
            $table->foreignId('user_id')
                  ->nullable() // Make it nullable if old orders might not have a user, or if a user might be deleted
                  ->constrained() // This assumes your users table is named 'users' and primary key is 'id'
                  ->onDelete('set null'); // If a user is deleted, their user_id in orders becomes NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id'); // Correctly drops the foreign key constraint
            $table->dropColumn('user_id'); // Then drops the column
        });
    }
};