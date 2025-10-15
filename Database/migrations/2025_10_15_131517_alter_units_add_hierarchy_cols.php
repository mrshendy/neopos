<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units','kind')) {
                $table->enum('kind', ['major','minor'])
                      ->default('minor')
                      ->after('abbreviation')
                      ->index();
            }

            if (!Schema::hasColumn('units','parent_id')) {
                // نضيف foreignId مباشرة بدون Doctrine
                $table->foreignId('parent_id')
                      ->nullable()
                      ->after('kind')
                      ->constrained('units')
                      ->nullOnDelete();
            }

            if (!Schema::hasColumn('units','ratio_to_parent')) {
                $table->decimal('ratio_to_parent', 18, 6)
                      ->nullable()
                      ->after('parent_id');
            }

            if (!Schema::hasColumn('units','is_default_minor')) {
                $table->boolean('is_default_minor')
                      ->default(true)
                      ->after('ratio_to_parent')
                      ->index();
            }

            if (!Schema::hasColumn('units','status')) {
                $table->enum('status', ['active','inactive'])
                      ->default('active')
                      ->after('is_default_minor')
                      ->index();
            }

            // ملاحظة: لم نتحقق من وجود فهرس فريد على code لتفادي الاعتماد على DBAL.
            // لو احتجته لاحقًا يمكنك إضافته بميجريشن منفصل.
        });
    }

    public function down(): void {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units','status')) $table->dropColumn('status');
            if (Schema::hasColumn('units','is_default_minor')) $table->dropColumn('is_default_minor');
            if (Schema::hasColumn('units','ratio_to_parent')) $table->dropColumn('ratio_to_parent');

            if (Schema::hasColumn('units','parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }

            if (Schema::hasColumn('units','kind')) $table->dropColumn('kind');

            // لم نضف unique(code) هنا، فلا حاجة لإزالته.
        });
    }
};
