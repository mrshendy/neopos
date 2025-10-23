<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // أضف الأعمدة + العلاقات بشرط عدم وجودها (للتعامل مع حالة التنفيذ الجزئي السابق)
        Schema::table('finance_receipts', function (Blueprint $table) {
            if (!Schema::hasColumn('finance_receipts', 'source_finance_settings_id')) {
                $table->unsignedBigInteger('source_finance_settings_id')->nullable()->after('finance_settings_id');
                $table->foreign('source_finance_settings_id')
                      ->references('id')->on('finance_settings')->nullOnDelete();
            }

            if (!Schema::hasColumn('finance_receipts', 'target_finance_settings_id')) {
                $table->unsignedBigInteger('target_finance_settings_id')->nullable()->after('source_finance_settings_id');
                $table->foreign('target_finance_settings_id')
                      ->references('id')->on('finance_settings')->nullOnDelete();
            }

            if (!Schema::hasColumn('finance_receipts', 'handover_date')) {
                $table->date('handover_date')->nullable()->after('receipt_date');
            }

            if (!Schema::hasColumn('finance_receipts', 'delivered_by_user_id')) {
                $table->unsignedBigInteger('delivered_by_user_id')->nullable()->after('handover_date');
                $table->foreign('delivered_by_user_id')
                      ->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('finance_receipts', 'received_by_user_id')) {
                $table->unsignedBigInteger('received_by_user_id')->nullable()->after('delivered_by_user_id');
                $table->foreign('received_by_user_id')
                      ->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('finance_receipts', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('received_by_user_id');
                $table->foreign('created_by')
                      ->references('id')->on('users')->nullOnDelete();
            }
        });

        // ✅ أسماء فهارس قصيرة لتفادي طول الاسم
        Schema::table('finance_receipts', function (Blueprint $table) {
            if (Schema::hasColumn('finance_receipts', 'source_finance_settings_id')
                && Schema::hasColumn('finance_receipts', 'target_finance_settings_id')) {
                $table->index(['source_finance_settings_id', 'target_finance_settings_id'], 'idx_fr_src_tgt');
            }

            if (Schema::hasColumn('finance_receipts', 'handover_date')) {
                $table->index('handover_date', 'idx_fr_handover_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('finance_receipts', function (Blueprint $table) {
            // احذف الفهارس بالأسماء القصيرة
            try { $table->dropIndex('idx_fr_src_tgt'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_fr_handover_date'); } catch (\Throwable $e) {}

            // ثم العلاقات + الأعمدة لو موجودة
            if (Schema::hasColumn('finance_receipts', 'source_finance_settings_id')) {
                try { $table->dropForeign(['source_finance_settings_id']); } catch (\Throwable $e) {}
                $table->dropColumn('source_finance_settings_id');
            }
            if (Schema::hasColumn('finance_receipts', 'target_finance_settings_id')) {
                try { $table->dropForeign(['target_finance_settings_id']); } catch (\Throwable $e) {}
                $table->dropColumn('target_finance_settings_id');
            }
            if (Schema::hasColumn('finance_receipts', 'handover_date')) {
                $table->dropColumn('handover_date');
            }
            if (Schema::hasColumn('finance_receipts', 'delivered_by_user_id')) {
                try { $table->dropForeign(['delivered_by_user_id']); } catch (\Throwable $e) {}
                $table->dropColumn('delivered_by_user_id');
            }
            if (Schema::hasColumn('finance_receipts', 'received_by_user_id')) {
                try { $table->dropForeign(['received_by_user_id']); } catch (\Throwable $e) {}
                $table->dropColumn('received_by_user_id');
            }
            if (Schema::hasColumn('finance_receipts', 'created_by')) {
                try { $table->dropForeign(['created_by']); } catch (\Throwable $e) {}
                $table->dropColumn('created_by');
            }
        });
    }
};