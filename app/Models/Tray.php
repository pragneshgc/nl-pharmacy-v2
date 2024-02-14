<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $TrayID
 * @property int $PrescriptionID
 * @property int $UserID
 * @property int $Type
 * @property int $Priority
 * @property int $Status
 * @property int $CreatedAt
 * @property int $UpdatedAt
 * @property int $ProcessedAt
 * @property int $DeletedAt
 */
class Tray extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Tray';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'TrayID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrescriptionID',
        'UserID',
        'Type',
        'Priority',
        'Status',
        'CreatedAt',
        'UpdatedAt',
        'ProcessedAt',
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
        'TrayID' => 'int',
        'PrescriptionID' => 'int',
        'UserID' => 'int',
        'Type' => 'int',
        'Priority' => 'int',
        'Status' => 'int',
        'CreatedAt' => 'timestamp',
        'UpdatedAt' => 'timestamp',
        'ProcessedAt' => 'timestamp',
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
        'ProcessedAt',
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