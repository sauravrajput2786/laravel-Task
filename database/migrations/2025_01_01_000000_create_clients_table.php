<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Runs against the "master" connection (the default connection).
     * Command: php artisan migrate
     */
    public function up(): void
    {
        Schema::connection('master')->create('clients', function (Blueprint $table): void {
            $table->id();
            $table->string('client_name');
            $table->string('client_code', 50)->unique();
            $table->string('db_server');
            $table->unsignedInteger('db_port')->default(3306);
            $table->string('db_name');
            $table->string('db_user');
            $table->text('db_password')->comment('Encrypted at rest via the Client model cast');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('master')->dropIfExists('clients');
    }
};
