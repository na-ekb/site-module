<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSitemapToPageMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_metas', function (Blueprint $table) {
            $table->unsignedFloat('priority',2,1)->default(0)->after('scripts');
            $table->unsignedTinyInteger('changefreq')->default(0)->after('scripts');
            $table->boolean('sitemap')->default(0)->after('scripts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_metas', function (Blueprint $table) {
            $table->dropColumn('priority', 'changefreq', 'sitemap');
        });
    }
}
