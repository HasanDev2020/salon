<?php namespace Hasan\Teams\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHasanTeamsEmp extends Migration
{
    public function up()
    {
        Schema::create('hasan_teams_emp', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->time('name');
            $table->integer('best_time');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('positions');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hasan_teams_emp');
    }
}
