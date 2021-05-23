<?php namespace Hasan\Teams\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanTeamsEmp extends Migration
{
    public function up()
    {
        Schema::table('hasan_teams_emp', function($table)
        {
            $table->string('name', 0)->nullable(false)->unsigned(false)->default(null)->change();
            $table->smallInteger('best_time')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('hasan_teams_emp', function($table)
        {
            $table->time('name')->nullable(false)->unsigned(false)->default(null)->change();
            $table->integer('best_time')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
