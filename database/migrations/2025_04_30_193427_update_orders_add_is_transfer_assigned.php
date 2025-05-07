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
            // Add id_transfers column and set foreign key constraint
            $table->unsignedBigInteger('id_transfers')->nullable()->after('id'); // adjust position as needed
            $table->foreign('id_transfers')->references('id')->on('transfers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['id_transfers']);
            $table->dropColumn('id_transfers');
        });
    }
};
