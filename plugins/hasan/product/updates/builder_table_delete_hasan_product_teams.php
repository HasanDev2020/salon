<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteHasanProductTeams extends Migration
{
    public function up()
    {
        Schema::dropIfExists('hasan_product_teams');
    }
    
    public function down()
    {
        Schema::create('hasan_product_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('person_name', 255);
            $table->integer('position');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
}
