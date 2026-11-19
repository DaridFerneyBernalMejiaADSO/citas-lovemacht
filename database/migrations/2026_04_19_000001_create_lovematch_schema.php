<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            $this->upgradeLegacyUsers();

            return;
        }

        Schema::create('users', function (Blueprint $table): void {
            $table->increments('id_user');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('profiles', function (Blueprint $table): void {
            $table->increments('id_profiles');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('phone', 15)->default('000');
            $table->date('birth_date')->nullable();
            $table->string('gender', 30)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('bio', 300)->default('');
            $table->string('profile_photo')->nullable();
            $table->boolean('is_demo')->default(false);
            $table->timestamps();
            $table->unsignedInteger('user_id')->unique();
            $table->foreign('user_id')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('swipes', function (Blueprint $table): void {
            $table->increments('id_swipe');
            $table->unsignedInteger('swiper_id');
            $table->unsignedInteger('target_id');
            $table->enum('action', ['like', 'pass']);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['swiper_id', 'target_id']);
            $table->index(['target_id', 'action']);
            $table->foreign('swiper_id')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('target_id')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('matches', function (Blueprint $table): void {
            $table->increments('id_match');
            $table->unsignedInteger('user_one_id');
            $table->unsignedInteger('user_two_id');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_one_id', 'user_two_id']);
            $table->index('user_two_id');
            $table->foreign('user_one_id')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('user_two_id')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->increments('id_message');
            $table->unsignedInteger('match_id');
            $table->unsignedInteger('sender_id');
            $table->string('body', 1000);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['match_id', 'created_at']);
            $table->foreign('match_id')->references('id_match')->on('matches')->cascadeOnDelete();
            $table->foreign('sender_id')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('support_requests', function (Blueprint $table): void {
            $table->increments('id_request');
            $table->unsignedInteger('user_id');
            $table->string('subject', 120);
            $table->string('message', 2000);
            $table->string('admin_response', 2000)->nullable();
            $table->enum('status', ['abierto', 'respondido', 'cerrado'])->default('abierto');
            $table->unsignedInteger('responded_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index(['status', 'created_at']);
            $table->foreign('user_id')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('responded_by')->references('id_user')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        throw new LogicException('Esta migración conserva y transforma datos existentes; no se puede revertir automáticamente.');
    }

    private function upgradeLegacyUsers(): void
    {
        if (! Schema::hasColumn('users', 'id_role_fk')) {
            return;
        }

        DB::statement("ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER email");
        DB::table('users')->where('id_role_fk', 1)->update(['role' => 'admin']);
        DB::table('users')->where('id_role_fk', '<>', 1)->update(['role' => 'user']);
        DB::statement('ALTER TABLE users DROP FOREIGN KEY users_role_fk');
        DB::statement('ALTER TABLE users DROP INDEX id_role_fk');
        DB::statement('ALTER TABLE users DROP COLUMN id_role_fk, DROP COLUMN passwordTime');
        DB::statement('ALTER TABLE users MODIFY user_name VARCHAR(255) NOT NULL, MODIFY email VARCHAR(255) NOT NULL, MODIFY password VARCHAR(255) NOT NULL, ADD COLUMN remember_token VARCHAR(100) NULL');

        if (Schema::hasTable('roles')) {
            Schema::drop('roles');
        }
    }
};
