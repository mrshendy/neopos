<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('inventory_settings')) {
            Schema::create('inventory_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                // نخزّن JSON (مثلاً {"policy":"block"} أو {"days":30} أو {"pattern":"INV-TRX-{YYYY}-{####}"})
                $table->json('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_settings');
    }
};
