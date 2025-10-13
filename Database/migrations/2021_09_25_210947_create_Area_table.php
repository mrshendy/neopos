<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // لو الجدول موجود، تخطّى الإنشاء لمنع 1050
        if (Schema::hasTable('area')) {
            return;
        }

        Schema::create('area', function (Blueprint $table) {
            $table->id();

            // حافظنا على نفس نمط الأسماء كما في city
            $table->unsignedBigInteger('id_country')->nullable(false);
            $table->unsignedBigInteger('id_governorate')->nullable(false);
            $table->unsignedBigInteger('id_city')->nullable(false);

            $table->text('name');                 // متوافق مع city
            $table->text('notes')->nullable();
            $table->string('user_add', 30);
            $table->string('account_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // فهارس
            $table->index(['id_country','id_governorate','id_city']);

            // === FKs (تُنفذ بعد تأكدنا إن الجداول موجودة) ===
            $table->foreign('id_country')
                  ->references('id')->on('country')
                  ->cascadeOnDelete();

            $table->foreign('id_governorate')
                  ->references('id')->on('governorate')
                  ->cascadeOnDelete();

            $table->foreign('id_city')
                  ->references('id')->on('city')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('area')) {
            Schema::table('area', function (Blueprint $table) {
                try { $table->dropForeign(['id_country']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['id_governorate']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['id_city']); } catch (\Throwable $e) {}
            });
        }
        Schema::dropIfExists('area');
    }
};
