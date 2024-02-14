<?php
namespace App\Builders;

use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class OrderBuilder
{
    private $query;
    private $user;
    public function order()
    {
        $this->user = Auth::user();

        $columns = [
            'Prescription.PrescriptionID',
            'Prescription.ReferenceNumber',
            'Prescription.DeliveryID',
            'Client.CompanyName',
            'Prescription.Status',
            'Prescription.SubStatus',
            'Prescription.JVM',
            'Prescription.ClientID'
        ];

        if ($this->user->role < 50) {
            $columns = [
                'Prescription.PrescriptionID',
                'Prescription.ReferenceNumber',
                'Prescription.DeliveryID',
                'Client.CompanyName',
                'Prescription.Status',
                'Prescription.SubStatus',
                'Prescription.JVM',
                'Prescription.ClientID'
            ];
        }

        $this->query = Prescription::query()->select($columns)
            ->leftJoin('Client', 'Prescription.ClientID', '=', 'Client.ClientID');

        return $this;
    }

    public function withPatient()
    {
        $this->query = $this->query
            ->selectRaw("CONCAT('<b>',Prescription.Name, ' ', Prescription.Surname, '</b><br>',
            COALESCE(Prescription.DAddress1, ''), ' ', COALESCE(Prescription.DAddress2,''), ' ', COALESCE(Prescription.DAddress3, ''), ' ', COALESCE(Prescription.DAddress4, ''),
            '<br>', COALESCE(Prescription.Postcode,''),', ' , c.Name) AS 'Patient Name/Address', Prescription.Status, Prescription.AirwayBillNumber")
            ->leftJoin('Country AS c', 'c.CountryID', '=', 'Prescription.DCountryCode');

        return $this;
    }

    public function forCSV()
    {
        $this->query = $this->query
            ->selectRaw("CONCAT(COALESCE(Prescription.DAddress1, ''), ' ',
            COALESCE(Prescription.DAddress2,''), ' ', COALESCE(Prescription.DAddress3, ''), ' ', COALESCE(Prescription.DAddress4, ''),
            COALESCE(Prescription.Postcode,'')) AS 'Patient Address'")
            ->selectRaw("c.Name AS Country, Prescription.TrackingCode")
            ->leftJoin('Country AS c', 'c.CountryID', '=', 'Prescription.DCountryCode')
            ->selectRaw("Prescription.Name AS Name, Prescription.Surname AS Surname");

        return $this;
    }

    public function withTrayStatus()
    {
        $this->query = $this->query
            ->selectRaw("DATE_FORMAT(FROM_UNIXTIME(Prescription.CreatedDate), '%e %b %Y %H:%i') AS 'Received Date'");

        if ($this->user->role == 20 || $this->user->role == 19) {
            $this->query = $this->query->selectRaw("Prescription.Exemption, Prescription.PaymentMethod, Prescription.UPSAccessPointAddress"); //checkboxes are not used here so no need
        } else {
            $this->query = $this->query->selectRaw("Prescription.Exemption, Prescription.PaymentMethod, COALESCE((SELECT Status FROM Tray WHERE Tray.PrescriptionID = Prescription.PrescriptionID AND Status = 1 LIMIT 1),0) AS disabled, Prescription.UPSAccessPointAddress");
        }

        return $this;
    }

    public function withoutTrayStatus()
    {
        $this->query = $this->query
            ->selectRaw("DATE_FORMAT(FROM_UNIXTIME(Prescription.CreatedDate), '%e %b %Y %H:%i') AS 'Received Date'")
            ->selectRaw("DATE_FORMAT(FROM_UNIXTIME(Prescription.UpdatedDate), '%e %b %Y %H:%i') AS 'Processed Date'");

        return $this;
    }

    public function filterByStatus(string $filter)
    {
        switch ($filter) {
            case 'new':
                $this->query = $this->query
                    ->where('Prescription.Status', '1')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'safety':
                $this->query = $this->query
                    ->where('Prescription.Status', '9')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'postponed':
                $this->query = $this->query
                    ->where('Prescription.Status', '5')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'approved':
                $this->query = $this->query
                    ->where('Prescription.Status', '2')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'dpd':
                $this->query = $this->query
                    ->where('Prescription.Status', '7')
                    ->where('Prescription.DeliveryID', '4')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'ups':
                $this->query = $this->query
                    ->where('Prescription.Status', '7')
                    ->where('Prescription.DeliveryID', '7')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'dhl':
                $this->query = $this->query
                    ->where('Prescription.Status', '7')
                    ->where('Prescription.DeliveryID', '10')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'rml':
                $this->query = $this->query
                    ->where('Prescription.Status', '7')
                    ->where('Prescription.DeliveryID', '5')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'awaiting':
                $this->query = $this->query
                    ->where('Prescription.Status', '7')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'shipped':
                $this->query = $this->query
                    ->where('Prescription.Status', '8')
                    ->whereRaw("UpdatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) AND UpdatedDate>=UNIX_TIMESTAMP(CURDATE())");
                break;
            case 'rejected':
                $this->query = $this->query
                    ->where('Prescription.Status', '3')
                    ->whereRaw("UpdatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) AND UpdatedDate>=UNIX_TIMESTAMP(CURDATE())");
                break;
            case 'queried':
                $this->query = $this->query
                    ->where('Prescription.Status', '4')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'call':
                $this->query = $this->query
                    ->where('Prescription.Status', '11')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'cancelled':
                $this->query = $this->query
                    ->where('Prescription.Status', '6')
                    ->whereRaw("UpdatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) AND UpdatedDate>=UNIX_TIMESTAMP(CURDATE())");
                break;
            case 'onhold':
                $this->query = $this->query
                    ->where('Prescription.Status', '10')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            case 'return':
                $this->query = $this->query
                    ->where('Prescription.Status', '16')
                    ->whereRaw("Prescription.CreatedDate<=UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY)");
                break;
            default:
                break;
        }
        return $this;
    }

    public function build()
    {
        return $this->query;
    }
}