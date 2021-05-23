<?php namespace Hasan\Product\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHasanProduct extends Migration
{
    public function up()
    {
        Schema::rename('hasan_product_products', 'hasan_product_');
    }
    
    public function down()
    {
        Schema::rename('hasan_product_', 'hasan_product_products');
    }
}
