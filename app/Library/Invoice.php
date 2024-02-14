<?php

namespace App\Library;

use App\Services\Pdf;
use App\Mail\InvoiceEmailBulk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class Invoice
{
    private $invoiceStatus = [
        0 => 'INCOMPLETE',
        1 => 'UNPAID',
        2 => 'PAID',
        3 => 'CREDITNOTE',
        4 => 'DELETED',
    ];
    /**
     * Get single invoice
     *
     * @param boolean $id
     * @return object
     */
    public function invoice($id = false, $clientInfo = false)
    {
        $columns = ['*'];
        $user = Auth::user();

        // if($user->role < 50){
        //     $columns = ['i.GrossAmount', 'i.AmountReceived', 'i.Status', 'i.Type', 'i.PaymentMethod', 'i.VAT', 'i.NetAmount'];
        // } else {
        $columns = [
            'i.InvoiceID AS Invoice #', 'i.SequenceID', 'i.InvoiceID', 'c.CompanyName AS Client', 'coi.Name AS Invoice Country',
            'i.NetAmount', 'i.VAT',
            'i.GrossAmount', 'i.AmountReceived', 'i.Type', 'i.Status', 'i.ClientID', 'c.TradingName AS TradingName'
        ];
        // }

        if ($clientInfo) {
            $columns[] = 'c.CompanyName';
            $columns[] = 'c.Address1 AS ClientAddress1';
            $columns[] = 'c.Address2 AS ClientAddress2';
            $columns[] = 'c.Address3 AS ClientAddress3';
            $columns[] = 'c.Address4 AS ClientAddress4';
            $columns[] = 'c.Postcode AS ClientPostcode';
            $columns[] = 'c.CountryID AS ClientCountryID';
            $columns[] = 'c.Telephone AS ClientTelephone';
            $columns[] = 'c.Mobile AS ClientMobile';
            $columns[] = 'c.Email AS ClientEmail';
            $columns[] = 'c.InvoiceEmail AS ClientInvoiceEmail';
            $columns[] = 'c.VAT AS ClientVAT';
            $columns[] = 'c.DisplayVAT AS ClientDisplayVAT';
            $columns[] = 'co.Name AS ClientCountryName';
        }

        $data = DB::table('Invoice AS i')->select($columns);
        $data = $data
            ->selectRaw("DATE_FORMAT(FROM_UNIXTIME(i.DateCreated), '%d/%m/%Y') AS 'Created Date'")
            ->selectRaw("IF(i.DatePaid=0, 'Not Paid', DATE_FORMAT(FROM_UNIXTIME(i.DatePaid), '%d/%m/%Y')) AS 'Paid Date'")
            ->selectRaw("IF(i.DateCompleted=0,'', DATE_FORMAT(FROM_UNIXTIME(i.DateCompleted), '%d/%m/%Y')) AS 'Completed Date'");

        if ($id) {
            $data = $data->where('i.InvoiceID', $id);
        }

        $data = $data->leftJoin('Client AS c', 'i.ClientID', '=', 'c.ClientID');

        $data = $data->leftJoin('Country AS coi', 'coi.CountryID', '=', 'i.CountryID');

        if ($clientInfo) {
            $data = $data->leftJoin('Country AS co', 'c.CountryID', '=', 'co.CountryID');
        }

        return $data;
    }

    public function checkToken($token)
    {
        $user = DB::table('PharmacyUser')->where('token', $token)->first();

        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     */
    public function invoiceItems($id)
    {
        return DB::table('InvoiceItem')->where('InvoiceID', $id)->orderBy('Date', 'ASC')->orderBy('ItemID', 'ASC');
    }

    public function setSearchParameters($f, $request, $data)
    {
        $filters = json_decode($f);
        $strict = json_decode($request->strict);
        $operator = $strict ? '=' : 'LIKE';

        if (isset($filters->timestamp)) {
            $dateFilter = $filters->timestamp == 'created_date' ? 'DateCreated' : 'DatePaid';
        } else {
            $dateFilter = 'DateCreated';
        }

        $itemsQueried = false;
        $prescriptionQueried = false;

        foreach ($filters as $key => $value) {
            if ($value != '') {
                switch ($key) {
                    case 'start_date':
                        $date = new \DateTime($value);
                        $date->setTime(00, 00, 00);
                        $date = $date->getTimestamp();

                        $data = $data->where("i.$dateFilter", '>', $date);

                        break;
                    case 'end_date':
                        $date = new \DateTime($value);
                        $date->setTime(23, 59, 59);
                        $date = $date->getTimestamp();

                        $data = $data->where("i.$dateFilter", '<', $date);

                        break;
                    case 'order_id':
                        if (!$itemsQueried) {
                            $data = $data->leftJoin('InvoiceItem AS ii', 'i.InvoiceID', '=', 'ii.InvoiceID');
                            $itemsQueried = true;
                        }

                        $value = preg_replace('/\t/', '', ltrim(rtrim($value)));
                        $valueArray = preg_split('/[\ \n\,]+/', $value);

                        $data = $data->whereIn('ii.PrescriptionID', $valueArray);

                        break;
                    case 'country':
                        if (!$itemsQueried) {
                            $data = $data->leftJoin('InvoiceItem AS ii', 'i.InvoiceID', '=', 'ii.InvoiceID');
                            $itemsQueried = true;
                        }

                        if (!$prescriptionQueried) {
                            $data = $data->leftJoin('Prescription AS p', 'ii.PrescriptionID', '=', 'p.PrescriptionID');
                            $prescriptionQueried = true;
                        }

                        if (in_array(1, $value, true)) {
                            array_push($value, 244);
                            array_push($value, 245);
                        }

                        if (in_array('1-northern-ireland', $value, true) && !in_array('1-great-britain', $value, true)) {
                            $data = $data->where('p.DPostCode', 'LIKE', "BT%");
                            array_push($value, 1);
                            $value = array_diff($value, ['1-northern-ireland']);
                        } else if (!in_array('1-northern-ireland', $value, true) && in_array('1-great-britain', $value, true)) {
                            $data = $data->where('p.DPostCode', 'NOT LIKE', "BT%");
                            array_push($value, 1);
                            $value = array_diff($value, ['1-great-britain']);
                        } else if (in_array('1-northern-ireland', $value, true) && in_array('1-great-britain', $value, true)) {
                            $value = array_diff($value, ['1-northern-ireland', '1-great-britain']);
                            array_push($value, 1);
                        }

                        $data = $data->whereIn('p.DCountryCode', $value);

                        break;
                    case 'status':
                        $data = $data->where('i.Status', '=', $value);
                        break;

                    case 'delivery':
                        if (!$itemsQueried) {
                            $data = $data->leftJoin('InvoiceItem AS ii', 'i.InvoiceID', '=', 'ii.InvoiceID');
                            $itemsQueried = true;
                        }

                        if (!$prescriptionQueried) {
                            $data = $data->leftJoin('Prescription AS p', 'ii.PrescriptionID', '=', 'p.PrescriptionID');
                            $prescriptionQueried = true;
                        }

                        $data = $data->where('p.DeliveryID', '=', $value);
                        break;

                    case 'statuses':
                        if (count($value) > 0) {
                            $data = $data->whereIn('i.Status', $value);
                        }

                        break;
                    case 'reference':
                        if (!$itemsQueried) {
                            $data = $data->leftJoin('InvoiceItem AS ii', 'i.InvoiceID', '=', 'ii.InvoiceID');
                            $itemsQueried = true;
                        }

                        $value = preg_replace('/\t/', '', ltrim(rtrim($value)));
                        $valueArray = preg_split('/[\ \n\,]+/', $value);

                        $data = $data->whereIn('ii.ReferenceNumber', $valueArray);

                        break;
                    case 'client':
                        $data = $data->where('i.ClientID', '=', $value);
                        break;

                    default:
                        break;
                }
            }
        }
        return $data;
    }

    public function createOrGetInvoice($clientID, $countryID)
    {
        $invoice = DB::table('Invoice')->where('ClientID', $clientID)->where('Status', 0)->where('Type', 0)->where('CountryID', $countryID)->first();

        $lastSequenceID = DB::table('Invoice')->where('ClientID', $clientID)->max('SequenceID');

        if (!$invoice) {
            $invoice = [
                'ParentInvoiceID' => 0,
                'SequenceID' => $lastSequenceID + 1,
                'ClientID' => $clientID,
                'CountryID' => $countryID,
                'DateCreated' => now()->timestamp,
                'DatePaid' => 0,
                'DateCompleted' => 0,
                'GrossAmount' => 0,
                'AmountReceived' => 0,
                'Status' => 0,
                'Type' => 0,
                'PaymentMethod' => 0,
                'VAT' => 0,
                'NetAmount' => 0,
            ];

            return DB::table('Invoice')->where('InvoiceID', DB::table('Invoice')->insertGetId($invoice))->first();
        }

        return $invoice;
    }
    /**
     * Generate an invoice from a prescription
     *
     * @param int $id
     * @return bool
     */
    public function generate($id): bool
    {
        $prescription = DB::table('Prescription AS p')
            ->select(['p.*', 'do.Type AS DoctorType', 'c.VAT AS ClientVAT', 'co.Name AS CountryName'])
            ->where('PrescriptionID', $id)
            ->leftJoin('Client as c', 'c.ClientID', '=', 'p.ClientID')
            ->leftJoin('DoctorAddress as do', 'do.DoctorAddressID', '=', 'p.DoctorAddressID')
            ->leftJoin('Country as co', 'co.CountryID', '=', 'p.DCountryCode')
            ->first();

        $client = DB::table('Client')->where('ClientID', $prescription->ClientID)->first();

        if ($prescription) {
            $invoice = $this->createOrGetInvoice($prescription->ClientID, $prescription->DCountryCode); //make it country specific
            $items = [];

            //if the invoice item already exists don't insert it again or update it?
            if (DB::table('InvoiceItem')->where('PrescriptionID', $id)->exists()) {
                DB::table('InvoiceItem')->where('PrescriptionID', $id)->delete();
                // return false;
            }

            $products = DB::table('Product AS p')
                ->select(['p.*', 'pc.VAT AS VAT'])
                ->leftJoin('ProductCode AS pc', 'pc.Code', '=', 'p.Code')
                ->where('p.PrescriptionID', $id)->get();

            if (!$products) {
                return false;
            }



            foreach ($products as $product) {
                $price = 0;
                $priceVat = 0;
                $vat = 0;

                if ($prescription->ClientID == config('app.clientid_exception') && $prescription->DCountryCode == config('app.countryid_exception')) {
                    $vat = $client->VAT;
                }

                if ($prescription->ClientID == config('app.clientid_exception_bims')) {
                    $vat = $client->VAT;
                }

                if ($prescription->ClientID == config('app.clientid_exception') && $prescription->DCountryCode != config('app.countryid_exception')) {
                    $vat = 0;
                }

                $labels = DB::table('PharmacyLabel')->where('ProductID', $product->ProductID)->get(); // the labels get generated when the pharmacy label is printed

                foreach ($labels as $label) {
                    $pricing = DB::table('Pricing')->where('Code', $label->Code)->where('Quantity', $label->Dosage)->where('ClientID', $prescription->ClientID)->first();

                    if (!$pricing) {
                        $pricing = DB::table('Pricing')->where('Code', $label->Code)->where('ClientID', $prescription->ClientID)->first();
                    }

                    if (!$pricing) {
                        $pricing = DB::table('Pricing')->where('Code', $label->Code)->where('Quantity', $label->Dosage)->first(); //bug here
                    }

                    if (!$pricing) {
                        $pricing = DB::table('Pricing')->where('Code', $label->Code)->first();
                    }

                    if ($pricing) {
                        $dosage = $label->Dosage;

                        if ($label->Code == '1159813' && $dosage < 10) {
                            $dosage = 10 * $dosage;
                        }

                        if ($pricing->Quantity == $label->Dosage) {
                            $price += $pricing->Price;
                        } else {
                            $price += (($pricing->Price / $pricing->Quantity) * $dosage);
                        }
                    }
                }

                // if($prescription->DoctorType == 1){
                //     $vat = 0;
                // } else {
                if ($vat > 0) {
                    $productVat = $product->VAT;

                    $priceVat = ($productVat / 100) * $price;
                } else {
                    $priceVat = 0;
                }
                // }

                //insert invoice item for product entry
                $items[] = DB::table('InvoiceItem')->insertGetId([
                    'InvoiceID' => $invoice->InvoiceID,
                    'PrescriptionID' => $id,
                    'Description' => $product->Description . ' (Dosage: ' . $product->Dosage . ')',
                    'Date' => $prescription->UpdatedDate,
                    'DoctorID' => $prescription->DoctorID,
                    'ProductID' => $product->ProductID,
                    'ProductCode' => $product->Code,
                    'UnitCost' => $price,
                    'Quantity' => $product->Quantity,
                    'VAT' => $priceVat,
                    'ReferenceNumber' => $prescription->ReferenceNumber,
                    'Type' => 1,
                    'Status' => 1,
                ]);

                //insert invoice item for shipping entry
                $postcodeTwo = strtoupper(substr($prescription->DPostcode, 0, 2));
                $price = 0;
                $shippingVat = 0;
                $dispensingVat = 0;
                $description = 'SHIPPING';

                //start insertion shipping entry
                if ($prescription->DCountryCode == 1 && in_array($postcodeTwo, ['BT', 'GY', 'JE'])) {
                    $description = 'SHIPPING(British Channel Islands)';
                    //242 is North Ireland
                    if ($shippingPricing = DB::table('Pricing')->where('Code', '242')->where('ClientID', $prescription->ClientID)->where('Type', 2)->first()) {
                        $price = $shippingPricing->Price;
                    } else {
                        $shippingPricing = DB::table('Pricing')
                            ->where('Code', $prescription->DCountryCode)
                            ->where('ClientID', $prescription->ClientID)
                            ->where('Type', 2)
                            ->first();

                        if (!$shippingPricing) {
                            //look for a bug here
                            $shippingPricing = DB::table('Pricing')
                                ->where('Code', $prescription->DCountryCode)
                                ->where('Type', 2)
                                ->first();
                        }

                        $price = $shippingPricing->Price;

                        if ($postcodeTwo == 'BT') {
                            $description = 'SHIPPING Northern Ireland';
                        }
                    }
                } else if ($prescription->ClientID == config('app.clientid_exception')) {
                    $price = 0;
                } else {
                    $description = "SHIPPING ($prescription->CountryName)";

                    if ($shippingPricing = DB::table('Pricing')->where('Code', $prescription->DCountryCode)->where('ClientID', $prescription->ClientID)->first()) {
                        $price = $shippingPricing->Price;
                    } else {
                        $shippingPricing = DB::table('Pricing')
                            ->where('Code', $prescription->DCountryCode)
                            ->where('Type', 2)
                            ->first();

                        if (!$shippingPricing) {
                            return false;
                        }

                        $price = $shippingPricing->Price;
                    }
                }

                $shippingVat = ($vat / 100) * $price;

                $clientDispensingFee = DB::table('Client')->where('ClientID', $prescription->ClientID)->value('DispensingFee');

                //inserting the dispensing fee
                if ($clientDispensingFee != 0) {
                    $dispensingVat = ($vat / 100) * $clientDispensingFee;

                    $items[] = DB::table('InvoiceItem')->insertGetId([
                        'InvoiceID' => $invoice->InvoiceID,
                        'PrescriptionID' => $id,
                        'Description' => 'DISPENSING FEE',
                        'Date' => $prescription->UpdatedDate,
                        'DoctorID' => $prescription->DoctorID,
                        'ProductID' => $product->ProductID,
                        'ProductCode' => $product->Code,
                        'UnitCost' => $clientDispensingFee,
                        'Quantity' => 1,
                        'VAT' => $dispensingVat,
                        'ReferenceNumber' => $prescription->ReferenceNumber,
                        'Type' => 4,
                        'Status' => 1,
                    ]);
                }

                //inserting the shipping fee
                $items[] = DB::table('InvoiceItem')->insertGetId([
                    'InvoiceID' => $invoice->InvoiceID,
                    'PrescriptionID' => $id,
                    'Description' => $description,
                    'Date' => $prescription->UpdatedDate,
                    'DoctorID' => $prescription->DoctorID,
                    'ProductID' => $product->ProductID,
                    'ProductCode' => $product->Code,
                    'UnitCost' => $price,
                    'Quantity' => $product->Quantity,
                    'VAT' => $shippingVat,
                    'ReferenceNumber' => $prescription->ReferenceNumber,
                    'Type' => 2,
                    'Status' => 1,
                ]);
                //end insertion for shipping entry
            }
        } else {
            return false;
        }

        //finally update invoice with the correct values
        $this->updateInvoiceValues($invoice->InvoiceID, $items);

        return true;
    }

    public function updateInvoiceValues($id, $items)
    {
        $invoiceItems = DB::table('InvoiceItem')->whereIn('ItemID', $items)->get();
        $invoice = DB::table('Invoice')->where('InvoiceID', $id)->first();

        $net = $invoice->NetAmount;
        $vat = $invoice->VAT;

        foreach ($invoiceItems as $invoiceItem) {
            $net += $invoiceItem->UnitCost;
            $vat += $invoiceItem->VAT;
        }

        DB::table('Invoice')->where('InvoiceID', $id)->update([
            'GrossAmount' => $net + $vat,
            'NetAmount' => $net,
            'VAT' => $vat,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $status
     * @return void
     */
    public function updateInvoiceStatus($id, $status)
    {
        $invoice = DB::table('Invoice')->where('InvoiceID', $id)->first();

        $update = ['Status' => $status];

        if ($status == 2) {
            $update['DatePaid'] = now()->timestamp;
            $update['AmountReceived'] = $invoice->GrossAmount;
        }

        if ($status == 1) {

            $update['DateCompleted'] = now()->timestamp;
            $update = $this->recalculateInvoice($id, $update);

            //recalculate VAT, Gross amount and Net amount
            // $invoiceItems = DB::table('InvoiceItem')->where('InvoiceID', $id)->get();

            // $vat = 0;
            // $grossAmount = 0;
            // $netAmount = 0;

            // foreach ($invoiceItems as $item) {
            //     $vat += $item->VAT;
            //     $grossAmount += $item->UnitCost + $item->VAT;
            //     $netAmount += $item->UnitCost;
            // }

            // $update['VAT'] = $vat;
            // $update['GrossAmount'] = $grossAmount;
            // $update['NetAmount'] = $netAmount;

            // //create a new invoice if one does not already exist for this company
        }

        DB::table('Invoice')->where('InvoiceID', $id)->update($update);

        //Ticket ESA-18 - Point 3    
        //return $status != 0 ? $this->createOrGetInvoice($invoice->ClientID, $invoice->CountryID) : true;
        return true;
        
    }

    public function recalculateInvoice($id, $update = [])
    {
        $invoiceItems = DB::table('InvoiceItem')->where('InvoiceID', $id)->get();

        $vat = 0;
        $grossAmount = 0;
        $netAmount = 0;

        foreach ($invoiceItems as $item) {
            $vat += $item->VAT;
            $grossAmount += $item->UnitCost + $item->VAT;
            $netAmount += $item->UnitCost;
        }

        $update['VAT'] = $vat;
        $update['GrossAmount'] = $grossAmount;
        $update['NetAmount'] = $netAmount;

        return $update;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $request
     * @return void
     */
    public function addItem($id, $request)
    {
        $item = $request->all();
        $item['InvoiceID'] = $id;
        $item['Status'] = 0;
        $item['Quantity'] = 1;
        $item['Date'] = now()->timestamp;

        if ($item['Type'] == 3) {
            $item['UnitCost'] = -$item['UnitCost'];
            $item['VAT'] = -$item['VAT'];
        }

        $insert = DB::table('InvoiceItem')->insertGetId($item);

        $update = $this->recalculateInvoice($id);
        DB::table('Invoice')->where('InvoiceID', $id)->update($update);

        return $insert;
    }

    public function deleteItem($invoiceid, $itemid)
    {
        $delete = DB::table('InvoiceItem')->where('ItemID', $itemid)->delete();

        $update = $this->recalculateInvoice($invoiceid);
        DB::table('Invoice')->where('InvoiceID', $invoiceid)->update($update);

        return $delete;
    }

    function generatePDF($id, $request)
    {            
        if (isAzureStorageEnabled() && !Storage::disk('azure')->exists("invoices/$id.pdf")) {
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            $view = $this->previewInvoice($id);

            $pdf = DomPDF::loadHTML($view);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            Storage::disk('azure')->put("invoices/$id.pdf", $pdf->output());
            return true;

        } else if (!Storage::disk('invoices')->exists("$id.pdf")) {
            $token = $request->token;

            if (!$token || !(new Invoice())->checkToken($token)) {
                return false;
            }

            if (config('app.chrome') != '') {
                $pdf = new Pdf; //In case no PDF exists this shall render
                $url = url('/');
                $pdf->render($id, "$url/invoice/$id/preview?token=$token", 'invoices');
                return true;
            } else {
                ini_set('memory_limit', '-1');
                set_time_limit(0);

                $view = $this->previewInvoice($id);

                $pdf = DomPDF::loadHTML($view);
                $pdf->setPaper('A4', 'portrait');
                $pdf->render();
                Storage::disk('invoices')->put("$id.pdf", $pdf->output());
                return true;
            }
        }

        return true;
    }

    public function setAllCompleteAndMail($id, $request)
    {
        //get all invoices that are incomplete (0) status, loop through them, set status to complete (1) and send the email for each of the invoices
        $invoices = DB::table('Invoice AS i')->select(['i.*', 'c.CompanyName AS Client', 'c.InvoiceEmail AS ClientInvoiceEmail'])
            ->leftJoin('Client AS c', 'i.ClientID', '=', 'c.ClientID')
            ->where('i.Status', 0)
            ->where('i.Type', 0)->where('i.ClientID', $id)->get();

        $company = (new Order())->getShipperData();

        $files = [];

        foreach ($invoices as $invoice) {
            $this->updateInvoiceStatus($invoice->InvoiceID, 1);
            $this->generatePDF($invoice->InvoiceID, $request);
            if (isAzureStorageEnabled()) {
                $files[] = Storage::disk('azure')->url("invoices/$invoice->InvoiceID.pdf");
            } else {
                $files[] = storage_path("app/invoices/$invoice->InvoiceID.pdf");
            }
        }

        $mail = new InvoiceEmailBulk($id, $invoice->Client, $company->CompanyName, $files);

        Mail::to(explode(',', $invoice->ClientInvoiceEmail))->send($mail);

        return true;
    }

    function setAllPaid($id)
    {
        return DB::table('Invoice')->where('Status', 1)->where('Type', 0)->where('ClientID', $id)
            ->update(['Status' => 2, 'DatePaid' => now()->timestamp, 'AmountReceived' => DB::raw('GrossAmount')]);
    }

    public function previewInvoice($id)
    {
        $invoice = (new Invoice())->invoice($id, true)->first();
        $invoice->Items = (new Invoice())->invoiceItems($id)
            ->select([
                'InvoiceItem.*',
                'ProductCode.VAT AS VATRate'
            ])
            ->leftJoin('ProductCode', 'ProductCode.Code', '=', 'InvoiceItem.ProductCode')
            ->orderBy('Date', 'DESC')
            ->get();

        $invoice->Company = (new Order())->getShipperData();
        $invoice->ShippingCount = 0;
        $invoice->ShippingPrice = 0;
        $invoice->ShippingVAT = 0;
        $invoice->ProductsCount = 0;
        $invoice->ProductsPrice = 0;
        $invoice->ProductsVAT = 0;

        $invoice->OtherCount = 0;
        $invoice->OtherPrice = 0;
        $invoice->OtherVAT = 0;

        $invoice->VATCounter = [];

        foreach ($invoice->Items as $item) {
            $item->Date = date('d/m/Y', $item->Date);

            $item->UnitCost = number_format((float) $item->UnitCost, 2);
            $item->VATC = number_format((float) $item->VAT, 3);
            $item->VAT = number_format((float) $item->VAT, 2);
            
            $item->Total = number_format(((float) $item->UnitCost + (float) $item->VAT), 2);

            if ($item->Type == 1) {
                $invoice->ProductsCount++;
                $previousProductItem = $item;
                $invoice->ProductsPrice += $item->UnitCost;
                $invoice->ProductsVAT += $item->VATC;

                if (!property_exists($invoice, 'ProductToDate')) {
                    $invoice->ProductToDate = $item->Date;
                }
            } else if ($item->Type == 2) {
                $invoice->ShippingCount++;
                $previousShippingItem = $item;
                $invoice->ShippingPrice += $item->UnitCost;
                $invoice->ShippingVAT += $item->VATC;

                if (!property_exists($invoice, 'ShippingToDate')) {
                    $invoice->ShippingToDate = $item->Date;
                }
            } else {
                $invoice->OtherCount++;
                $invoice->OtherPrice += $item->UnitCost;
                $invoice->OtherVAT += $item->VATC;
            }

            if ($item->VATRate == '' || $item->VATRate == null) {
                if ($item->VAT == '0.00') {
                    $item->VATRate = 0;
                } else {
                    $item->VATRate = number_format(((float)$item->VATC / $item->UnitCost) * 100, 2);
                }
            }

            if (isset($invoice->VATCounter[$item->VATRate])) {
                $invoice->VATCounter[$item->VATRate] += $item->VATC;
            } else {
                $invoice->VATCounter[$item->VATRate] = $item->VATC;
            }
        }

        //format absolutely everything
        if (isset($previousProductItem) && isset($previousShippingItem)) {
            $invoice->ProductFromDate = $previousProductItem->Date;
            $invoice->ShippingFromDate = $previousShippingItem->Date;
        } else {
            $invoice->ProductFromDate = date('d/m/Y');
            $invoice->ProductToDate = date('d/m/Y');
            $invoice->ShippingFromDate = date('d/m/Y');
            $invoice->ShippingToDate = date('d/m/Y');
        }


        foreach ($invoice->VATCounter as $key => $VAT) {
            $invoice->VATCounter[$key] = number_format((float) $VAT, 2);
            //$invoice->VATCounter[$key] = number_format(floor($VAT*100)/100, 2);
        }
        
        $invoice->CalculatedSubTotal = $invoice->ProductsPrice + $invoice->ShippingPrice + $invoice->OtherPrice;
        $invoice->CalculatedVAT = $invoice->ProductsVAT + $invoice->ShippingVAT + $invoice->OtherVAT;
        $invoice->CalculatedTotal = $invoice->CalculatedSubTotal + $invoice->CalculatedVAT;

        $invoice->BalanceDue = number_format((float) $invoice->CalculatedTotal - (float) $invoice->AmountReceived, 2);

        $invoice->CalculatedSubTotal = number_format($invoice->CalculatedSubTotal, 2);
        $invoice->CalculatedVAT = number_format((float) $invoice->CalculatedVAT, 2);
        //$invoice->CalculatedVAT = number_format(floor($invoice->CalculatedVAT*100)/100, 2);
        $invoice->CalculatedTotal = number_format($invoice->CalculatedTotal, 2);

        $invoice->StatusString = $this->invoiceStatus[$invoice->Status];

        $invoice->ShippingCount = number_format((float) $invoice->ShippingCount, 2);
        $invoice->ShippingPrice = number_format((float) $invoice->ShippingPrice, 2);
         $invoice->ShippingVAT = number_format((float) $invoice->ShippingVAT, 2);
        //$invoice->ShippingVAT = number_format(floor($invoice->ShippingVAT*100)/100, 2);
        $invoice->ProductsCount = number_format((float) $invoice->ProductsCount, 2);
        $invoice->ProductsPrice = number_format((float) $invoice->ProductsPrice, 2);
        $invoice->ProductsVAT = number_format((float) $invoice->ProductsVAT, 2);
        //$invoice->ProductsVAT = number_format(floor($invoice->ProductsVAT*100)/100, 2);        
        $invoice->OtherCount = number_format((float) $invoice->OtherCount, 2);
        $invoice->OtherPrice = number_format((float) $invoice->OtherPrice, 2);
        $invoice->OtherVAT = number_format((float) $invoice->OtherVAT, 2);
        //$invoice->OtherVAT = number_format(floor($invoice->OtherVAT*100)/100, 2);

        return View::make('invoice.layout', compact(['invoice']))->render();
    }
}
