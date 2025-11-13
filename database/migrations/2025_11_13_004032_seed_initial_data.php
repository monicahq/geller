<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Instance;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Instance::factory()->create([
            'url' => config('app.app_instance_url'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
