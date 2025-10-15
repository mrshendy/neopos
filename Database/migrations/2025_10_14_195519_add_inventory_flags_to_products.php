<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'track_batch')) {
                $table->boolean('track_batch')->default(false)->after('opening_stock');
            }
            if (!Schema::hasColumn('products', 'track_serial')) {
                $table->boolean('track_serial')->default(false)->after('track_batch');
            }
            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->integer('reorder_level')->nullable()->after('track_serial');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'track_batch'))   $table->dropColumn('track_batch');
            if (Schema::hasColumn('products', 'track_serial'))  $table->dropColumn('track_serial');
            if (Schema::hasColumn('products', 'reorder_level')) $table->dropColumn('reorder_level');
        });
    }
};
