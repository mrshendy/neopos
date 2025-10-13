<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->json('name');               // {ar,en}
            $table->json('position')->nullable(); // {ar,en}

            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->enum('preferred_channel', ['phone','email','sms','whatsapp'])->nullable();

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_contacts');
    }
};
