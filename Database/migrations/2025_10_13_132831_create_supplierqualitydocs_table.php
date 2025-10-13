<?php
// database/migrations/2025_10_13_000007_create_supplier_quality_docs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    if (!Schema::hasTable('supplier_quality_docs')) {
        Schema::create('supplier_quality_docs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $t->foreignId('quality_doc_type_id')->constrained('quality_doc_types');
            $t->string('number')->nullable();
            $t->date('issue_date')->nullable();
            $t->date('expiry_date')->nullable();
            $t->string('file_path')->nullable();
            $t->enum('status',['valid','expired'])->default('valid');
            $t->timestamps();
            $t->softDeletes();

            // اسم فهرس قصير لتفادي 1059
            $t->index(['supplier_id','quality_doc_type_id','status'], 'sqdocs_sid_qtype_stat_idx');
        });
    }
}

    public function down(): void { Schema::dropIfExists('supplier_quality_docs'); }
};
