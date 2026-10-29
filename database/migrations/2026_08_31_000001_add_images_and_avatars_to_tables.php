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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('avatar_url');
            $table->string('department')->nullable()->after('job_title');
            $table->string('phone')->nullable()->after('department');
            $table->text('bio')->nullable()->after('phone');
            $table->string('role')->default('Executive Admin')->after('bio');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('description');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('name');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('requester_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('avatar_url');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('avatar_url');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_url', 'job_title', 'department', 'phone', 'bio', 'role']);
        });
    }
};
