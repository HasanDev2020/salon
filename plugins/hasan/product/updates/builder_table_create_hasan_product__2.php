<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHasanProduct2 extends Migration
{
    public function up()
    {
        Schema::create('hasan_product_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->double('price', 10, 0);
            $table->text('description')->nullable();
            $table->integer('cat_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hasan_product_');
    }
}
