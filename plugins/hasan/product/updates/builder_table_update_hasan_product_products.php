<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanProductProducts extends Migration
{
    public function up()
    {
        Schema::rename('hasan_product_', 'hasan_product_products');
    }
    
    public function down()
    {
        Schema::rename('hasan_product_products', 'hasan_product_');
    }
}
