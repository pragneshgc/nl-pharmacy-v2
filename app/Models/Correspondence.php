<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $Correspondence
 * @property int    $CreatedDate
 * @property int    $Status
 * @property int    $UserID
 * @property int    $DoctorID
 * @property int    $Type
 * @property string $ClientID
 * @property string $PrescriptionID
 * @property string $Message
 * @property string $Subject
 * @property string $ReferenceNumber
 */
class Correspondence extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Correspondence';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Correspondence';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ClientID',
        'PrescriptionID',
        'Message',
        'CreatedDate',
        'Status',
        'Subject',
        'ReferenceNumber',
        'UserID',
        'DoctorID',
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
        'Correspondence' => 'int',
        'ClientID' => 'string',
        'PrescriptionID' => 'string',
        'Message' => 'string',
        'CreatedDate' => 'int',
        'Status' => 'int',
        'Subject' => 'string',
        'ReferenceNumber' => 'string',
        'UserID' => 'int',
        'DoctorID' => 'int',
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