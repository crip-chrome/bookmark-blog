<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarkPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->index();
            $table->unsignedInteger('page_id')->unique();
            $table->unsignedInteger('date_added');
            $table->string('title');
            $table->string('url', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_pages');
    }
}
