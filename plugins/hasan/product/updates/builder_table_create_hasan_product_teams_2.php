<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHasanProductTeams2 extends Migration
{
    public function up()
    {
        Schema::create('hasan_product_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('positions');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hasan_product_teams');
    }
}
