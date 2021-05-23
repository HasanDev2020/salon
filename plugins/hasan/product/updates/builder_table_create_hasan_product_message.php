<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHasanProductMessage extends Migration
{
    public function up()
    {
        Schema::create('hasan_product_message', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username');
            $table->text('text_message');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hasan_product_message');
    }
}
