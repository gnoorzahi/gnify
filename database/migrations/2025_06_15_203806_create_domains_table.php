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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('domain');
            $table->string('subdomain')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_https')->default(true);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->json('ssl_config')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->unique(['domain', 'subdomain']);
            $table->index('domain');
            $table->index('subdomain');
            $table->index('tenant_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
