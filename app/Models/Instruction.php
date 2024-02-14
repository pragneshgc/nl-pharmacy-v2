<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $InstructionID
 * @property int    $Lang
 * @property int    $Status
 * @property int    $Type
 * @property string $Description
 * @property string $Name
 */
class Instruction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Instruction';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'InstructionID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Description',
        'Lang',
        'Name',
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
        'InstructionID' => 'int',
        'Description' => 'string',
        'Lang' => 'int',
        'Name' => 'string',
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