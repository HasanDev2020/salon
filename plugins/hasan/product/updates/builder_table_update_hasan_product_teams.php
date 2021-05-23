<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanProductTeams extends Migration
{
    public function up()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->integer('position')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('hasan_product_teams', function($table)
        {
            $table->string('position', 255)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
