<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanProductTeams3 extends Migration
{
    public function up()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->time('best_time');
        });
    }
    
    public function down()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->dropColumn('best_time');
        });
    }
}
