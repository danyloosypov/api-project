<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneAvatarAndRoleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable(); // Add phone field
            $table->string('avatar')->nullable(); // Add avatar field
            $table->foreignId('role_id')->nullable()->constrained('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}
