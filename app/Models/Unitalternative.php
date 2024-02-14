<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $ProductNameAlternativeID
 * @property int    $ProductCodeID
 * @property int    $ClientID
 * @property int    $UserID
 * @property int    $CreatedAt
 * @property int    $UpdatedAt
 * @property int    $DeletedAt
 * @property string $AlternativeName
 */
class Unitalternative extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'UnitAlternative';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'UnitAlternativeID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ProductCodeID',
        'ClientID',
        'AlternativeUnit',
        'UserID',
        'CreatedAt',
        'UpdatedAt',
        'DeletedAt'
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
        'UnitAlternativeID' => 'int',
        'ProductCodeID' => 'int',
        'ClientID' => 'int',
        'AlternativeUnit' => 'string',
        'UserID' => 'int',
        'CreatedAt' => 'timestamp',
        'UpdatedAt' => 'timestamp',
        'DeletedAt' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'CreatedAt',
        'UpdatedAt',
        'DeletedAt'
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