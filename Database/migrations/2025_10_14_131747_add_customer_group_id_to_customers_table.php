<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_group_id')->nullable()->after('id');
        $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropForeign(['customer_group_id']);
        $table->dropColumn('customer_group_id');
    });
}
};