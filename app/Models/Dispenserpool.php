<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $DispenserPoolID
 * @property int    $PrescriptionID
 * @property int    $UserID
 * @property int    $Type
 * @property int    $Status
 * @property string $Date
 */
class Dispenserpool extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'DispenserPool';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'DispenserPoolID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrescriptionID',
        'UserID',
        'Date',
        'Type',
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
        'DispenserPoolID' => 'int',
        'PrescriptionID' => 'int',
        'UserID' => 'int',
        'Date' => 'string',
        'Type' => 'int',
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