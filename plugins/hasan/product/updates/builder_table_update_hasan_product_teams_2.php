<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanProductTeams2 extends Migration
{
    public function up()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->string('fb_url')->nullable();
            $table->string('snap_url')->nullable();
            $table->string('insta_url')->nullable();
            $table->string('twitter_url')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->dropColumn('fb_url');
            $table->dropColumn('snap_url');
            $table->dropColumn('insta_url');
            $table->dropColumn('twitter_url');
        });
    }
}
