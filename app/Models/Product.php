<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $ProductID
 * @property int    $PrescriptionID
 * @property string $GUID
 * @property string $Code
 * @property string $Description
 * @property string $Instructions
 * @property string $Instructions2
 * @property string $Quantity
 * @property string $Unit
 * @property int    $Dosage
 */
class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Product';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ProductID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrescriptionID',
        'GUID',
        'Code',
        'Description',
        'Instructions',
        'Instructions2',
        'Quantity',
        'Unit',
        'Dosage',
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
        'ProductID' => 'int',
        'PrescriptionID' => 'int',
        'GUID' => 'string',
        'Code' => 'string',
        'Description' => 'string',
        'Instructions' => 'string',
        'Instructions2' => 'string',
        'Quantity' => 'string',
        'Unit' => 'string',
        'Dosage' => 'int'
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