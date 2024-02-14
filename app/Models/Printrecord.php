<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $PrintRecordID
 * @property int $PrescriptionID
 * @property int $UserID
 * @property int $TNTLabel
 * @property int $DeliveryNote
 * @property int $PharmacyLabel
 * @property int $Invoice
 * @property int $Consignment
 * @property int $Type
 * @property int $Status
 * @property int $DoctorLetter
 * @property int $UPSLabel
 */
class Printrecord extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PrintRecord';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PrintRecordID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrescriptionID',
        'UserID',
        'TNTLabel',
        'DeliveryNote',
        'PharmacyLabel',
        'Invoice',
        'Consignment',
        'Type',
        'Status',
        'DoctorLetter',
        'UPSLabel'
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
        'PrintRecordID' => 'int',
        'PrescriptionID' => 'int',
        'UserID' => 'int',
        'TNTLabel' => 'int',
        'DeliveryNote' => 'int',
        'PharmacyLabel' => 'int',
        'Invoice' => 'int',
        'Consignment' => 'int',
        'Type' => 'int',
        'Status' => 'int',
        'DoctorLetter' => 'int',
        'UPSLabel' => 'int'
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