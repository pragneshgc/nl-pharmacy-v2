<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $PackProductID
 * @property int    $Order
 * @property int    $Instruction
 * @property int    $Status
 * @property string $Code
 * @property string $Description
 * @property string $Quantity
 * @property string $Dosage
 * @property string $Unit
 * @property string $ProductCode
 */
class Packproduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PackProduct';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PackProductID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Order',
        'Code',
        'Description',
        'Quantity',
        'Dosage',
        'Unit',
        'ProductCode',
        'Instruction',
        'Status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'PackProductID' => 'int',
        'Order' => 'int',
        'Code' => 'string',
        'Description' => 'string',
        'Quantity' => 'string',
        'Dosage' => 'string',
        'Unit' => 'string',
        'ProductCode' => 'string',
        'Instruction' => 'int',
        'Status' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}