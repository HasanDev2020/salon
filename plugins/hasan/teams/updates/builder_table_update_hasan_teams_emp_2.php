<?php namespace Hasan\Teams\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanTeamsEmp2 extends Migration
{
    public function up()
    {
        Schema::table('hasan_teams_emp', function($table)
        {
            $table->dateTime('best_time')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('hasan_teams_emp', function($table)
        {
            $table->smallInteger('best_time')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
