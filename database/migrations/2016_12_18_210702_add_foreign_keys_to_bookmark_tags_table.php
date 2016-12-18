<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToBookmarkTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookmark_tags', function (Blueprint $table) {
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmark_tags', function (Blueprint $table) {
            $table->dropForeign('bookmark_tags_bookmark_id_foreign');
            $table->dropForeign('bookmark_tags_tag_id_foreign');
        });
    }
}
