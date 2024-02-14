<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $PharmacyLabelID
 * @property int    $ProductID
 * @property int    $Pack
 * @property int    $Type
 * @property int    $Status
 * @property int    $Dosage
 * @property string $Instruction
 * @property string $Code
 * @property string $Description
 */
class Pharmacylabel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PharmacyLabel';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PharmacyLabelID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ProductID',
        'Instruction',
        'Pack',
        'Type',
        'Status',
        'Code',
        'Description',
        'Dosage'
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
        'PharmacyLabelID' => 'int',
        'ProductID' => 'int',
        'Instruction' => 'string',
        'Pack' => 'int',
        'Type' => 'int',
        'Status' => 'int',
        'Code' => 'string',
        'Description' => 'string',
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