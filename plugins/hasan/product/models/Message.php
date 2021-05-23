<?php namespace Hasan\Product\Models;

use Model;

/**
 * Model
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hasan_product_message';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
