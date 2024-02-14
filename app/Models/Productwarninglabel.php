<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $PWLID
 * @property int    $Type
 * @property int    $Status
 * @property string $WLID
 * @property string $ProductID
 */
class Productwarninglabel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ProductWarningLabel';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PWLID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'WLID',
        'ProductID',
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
        'PWLID' => 'int',
        'WLID' => 'string',
        'ProductID' => 'string',
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