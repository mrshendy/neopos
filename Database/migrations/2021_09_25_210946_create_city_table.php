<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityTable extends Migration {

public function up(): void
{
    // لو الجدول موجود، متعملش حاجة
    if (\Illuminate\Support\Facades\Schema::hasTable('city')) {
        return;
    }

    Schema::create('city', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_country');
        $table->unsignedBigInteger('id_governorate');
        $table->text('name');
        $table->text('notes')->nullable();
        $table->string('user_add', 30);
        $table->string('account_id')->nullable();
        $table->timestamps();
        $table->softDeletes();

        // أضف هنا الفهارس/المفاتيح الخارجية لو محتاجها
        // $table->foreign('id_country')->references('id')->on('Countries')->cascadeOnDelete();
        // $table->foreign('id_governorate')->references('id')->on('governorate')->cascadeOnDelete();
    });
}


	public function down()
	{
		Schema::drop('city');
	}
}
