<?php

use App\Models\Vault;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('iid');
            $table->uuid('id');
            $table->string('name');
            $table->timestamp('last_synced_at')->nullable();
            $table->foreignIdFor(Vault::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['vault_iid', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
