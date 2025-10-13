<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->enum('approval_type', ['credit','tax','pricing']);
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->json('before_value')->nullable();
            $table->json('after_value')->nullable();

            $table->enum('status', ['pending','approved','rejected','on_hold'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['approval_type','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_approvals');
    }
};
