<?php namespace Hasan\Product\Models;

use Model;

/**
 * Model
 */
class Teams extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'hasan_product_teams';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /* Realtions */
    public $attachOne = [
        'image'=>'System\Models\File'
    ];
}
