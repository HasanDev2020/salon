<?php namespace Hasan\Product\Models;

use Model;

/**
 * Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'hasan_product_';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /* Realtions */
    public $attachOne = [
    'image'=>'System\Models\File'
    ];

    public $attachMany = [
        'product_gallery'=>'System\Models\File'
    ];
}
