<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemsTableForCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (Schema::hasColumn('items', 'category_id')) {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->json('category_ids')->nullable();
        });
    }
}

    public function down()
{
    Schema::table('items', function (Blueprint $table) {
        if (Schema::hasColumn('items', 'category_ids')) {
            $table->dropColumn('category_ids');
        }

        if (!Schema::hasColumn('items', 'category_id')) {
            $table->integer('category_id')->nullable();
        }
    });
}
}
