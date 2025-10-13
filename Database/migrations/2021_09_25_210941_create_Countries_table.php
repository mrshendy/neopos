<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreatecountryTable extends Migration {

	public function up()
	{
		Schema::create('country', function(Blueprint $table) {
            $table->bigIncrements('id');
			$table->text('name');
			$table->text('notes')->nullable();
			$table->string('user_add', 30);
			$table->string('account_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('country');
	}
}
