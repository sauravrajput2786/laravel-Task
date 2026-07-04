<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The email -> client_code lookup index. A unique index on `email`
     * makes tenant resolution at login a single O(log n) indexed query
     * regardless of how many tenants (or how many users per tenant)
     * exist - see README for the full rationale.
     */
    public function up(): void
    {
        Schema::connection('master')->create('client_users', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('client_code', 50);
            $table->timestamps();

            $table->foreign('client_code')
                ->references('client_code')
                ->on('clients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->index('client_code');
        });
    }

    public function down(): void
    {
        Schema::connection('master')->dropIfExists('client_users');
    }
};
