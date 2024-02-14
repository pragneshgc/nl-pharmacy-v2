<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $PILID
 * @property int    $Status
 * @property int    $Type
 * @property string $InstructionID
 * @property string $ProductID
 */
class Productinstruction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ProductInstruction';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PILID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'InstructionID',
        'ProductID',
        'Status',
        'Type'
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
        'PILID' => 'int',
        'InstructionID' => 'string',
        'ProductID' => 'string',
        'Status' => 'int',
        'Type' => 'int'
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