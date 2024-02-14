<?php

namespace App\Library;

use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Undocumented class
 */
class API
{
    /**
     * Check order statuses on external server
     *
     * @return boolean|string
     */
    public function checkStatus()
    {
        // $clients = DB::table('Client')->where('Status', 1)->whereNotNull('PendingPharmacyURL')->whereNotNull('PendingPharmacyEndpoint')->get();

        // foreach ($clients as $client) {
        //     $options = [
        //         'base_uri' => $client->PendingPharmacyURL,
        //         'headers' => [
        //             'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',//JSON or XML?
        //         ]
        //     ];

        //     $client = new GuzzleHttp\Client($options);

        //     try {
        //         $response = $client->request('GET', $client->PendingPharmacyEndpoint, $options)->getBody()->getContents();
        //     } catch (\Throwable $th) {
        //         return false;
        //     }

        //     $response = json_decode($response);
        //     $difference = [];

        //     if($response == null){
        //         return false;
        //     }
        // }

        $baseURI = 'https://syscare.technology';
        $endpoint = '/esa/orders';

        // $baseURIEA = 'https://treateduk.azurewebsites.net';
        // $endpointEA = '/api/ESAPendingPharmacyOrder?code=sX6y7dbwzUaDYMG/KW6njhn8amzTapOWqP9TtpX7Pi8SZtlj7kiqLQ==';

        $baseURIEA = 'https://treateduk.azurewebsites.net';
        $endpointEA = '/api/ESAPendingPharmacyOrder?code=sX6y7dbwzUaDYMG/KW6njhn8amzTapOWqP9TtpX7Pi8SZtlj7kiqLQ==';

        if (\App::environment('local')) {
            // $baseURI = 'https://treated-admin-staging.azurewebsites.net';
            // $endpoint = '/Esa/Orders';
            $baseURI = 'https://syscare.technology';
            $endpoint = '/esa/orders';
        }

        $options = [
            'base_uri' => $baseURI,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',
                //JSON or XML?
            ]
        ];

        //send a GET request to the endpoint
        $client = new GuzzleHttp\Client($options);

        try {
            $response = $client->request('GET', $endpoint, $options)->getBody()->getContents();
        } catch (\Throwable $th) {
            return false;
        }

        $response = json_decode($response);
        $difference = [];

        if ($response == null) {
            return false;
        }

        //check eve adam
        $optionsEA = [
            'base_uri' => $baseURIEA,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',
                //JSON or XML?
            ]
        ];

        //send a GET request to the endpoint
        $clientEA = new GuzzleHttp\Client($optionsEA);

        try {
            $responseEA = $clientEA->request('GET', $endpointEA, $optionsEA)->getBody()->getContents();
        } catch (\Throwable $th) {
            return false;
        }

        $orderNumbersEA = [];
        $responseEA = json_decode($responseEA);

        if ($responseEA->success) {
            $orderNumbersEA = $responseEA->orderNumbers;
        }

        $referenceNumbers = array_merge($response->pendingPharmacyOrders, $orderNumbersEA); // (array) $response['pendingPharmacyOrders']; //tells us what is processed
        $orderCount = $response->pendingPrescriberCount; // $response['pendingPrescriberCount']; //that tells us what needs to be processed
        $success = $response->isSuccess; // $response['isSuccess'];

        $localCount = DB::table('Prescription')->select('ReferenceNumber')->whereIn('ReferenceNumber', $referenceNumbers)->get();

        if ($localCount->isNotEmpty()) {
            $localCountArray = $localCount->pluck('ReferenceNumber')->all();
        } else {
            $localCountArray = [];
        }

        //hack for testkits
        $localCountTestKit = DB::table('TestKit')->select('ReferenceNumber')
            ->where('Count', '!=', 1)->whereIn('ReferenceNumber', $referenceNumbers)->get();

        if ($localCountTestKit->isNotEmpty()) {
            $testKitArray = $localCountTestKit->pluck('ReferenceNumber')->all();
            $localCountArray = array_merge($localCountArray, $testKitArray);
        }

        $difference = array_diff($referenceNumbers, $localCountArray); //this gives us the difference between two arrays

        if ($success) {
            DB::table('SyncOrder')->delete();

            foreach ($difference as $number) {
                if (in_array($number, $orderNumbersEA)) {
                    DB::table('SyncOrder')->insert(['ClientID' => 51, 'Type' => 1, 'Value' => $number]);
                } else {
                    DB::table('SyncOrder')->insert(['ClientID' => 50, 'Type' => 1, 'Value' => $number]);
                }
            }

            DB::table('SyncOrder')->insert(['ClientID' => 50, 'Type' => 2, 'Value' => $orderCount]);
        }

        return $response;
    }

    /**
     * Get the pending orders and count from database
     */
    public function checkOrders(): array
    {
        $response = [
            'pendingPharmacyOrders' => [],
            'pendingPrescriberCount' => 0,
            'pendingPharmacyOrdersCount' => 0,
        ];

        $pendingPharmacyOrders = DB::table('SyncOrder')
            ->select(['SyncOrder.SyncOrderID', 'SyncOrder.ClientID', 'SyncOrder.Value', 'Client.CompanyName'])
            ->leftJoin('Client', 'Client.ClientID', '=', 'SyncOrder.ClientID')
            ->where('SyncOrder.Type', 1)
            ->get();

        $pendingPrescriberCount = DB::table('SyncOrder')
            ->where('Type', 2)
            ->value('Value');

        if ($pendingPharmacyOrders->isNotEmpty()) {
            $response['pendingPharmacyOrders'] = $pendingPharmacyOrders;
            $response['pendingPharmacyOrdersCount'] = count($pendingPharmacyOrders);
        }

        if ($pendingPrescriberCount) {
            $response['pendingPrescriberCount'] = $pendingPrescriberCount;
        }

        return $response;
    }

    /**
     * Process an order approval with EveAdam
     *
     * @param int $referenceNumber
     * @return boolean|string
     */
    public function processApproval($referenceNumber)
    {
        $baseURI = 'http://localhost:1003';

        //might need to be in JSON
        $data = [
            'OrderID' => $referenceNumber,
            'Status' => 'Approved',
        ];

        $options = [
            'base_uri' => $baseURI,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',
                //JSON or XML?
            ],
            'form_params' => $data,
        ];

        //send a POST request to the endpoint
        $endpoint = '/api/echo';
        $client = new GuzzleHttp\Client($options);

        try {
            $response = $client->request('POST', $endpoint, $options)->getBody()->getContents();
        } catch (\Throwable $th) {
            return false;
        }

        return $response;
    }

    /**
     * Check if the prescription with reference number exist or is duplicated
     *
     * @param int $referenceNumber
     * @return array
     */
    public function checkOrder($referenceNumber)
    {
        $count = DB::table('Prescription')->where('ReferenceNumber', $referenceNumber)->where('Status', 20)->count();

        if ($count == 0) {
            return ['error' => true, 'message' => "Order with reference number $referenceNumber not found or in wrong status", 'code' => 404];
        } else if ($count > 1) {
            return ['error' => true, 'message' => "Multiple orders with reference number $referenceNumber found", 'code' => 409];
        } else {
            $order = DB::table('Prescription')->where('ReferenceNumber', $referenceNumber)->where('Status', 20)->first();
            return ['error' => false, 'order' => $order];
        }
    }

    /**
     * Validate an order address
     * This entire function needs to be refactored
     *
     * @param int $id
     * @return array
     */
    public function validateAddress($id, $addressChange = false)
    {
        $orderLibrary = new \App\Library\Order;

        $order = $orderLibrary->getOrderDetails($id);

        if ($addressChange) {
            $order->DAddress1 = $addressChange['DAddress1'];
            $order->DAddress2 = $addressChange['DAddress2'];
            $order->DAddress3 = $addressChange['DAddress3'];
            $order->DAddress4 = $addressChange['DAddress4'];
            $order->DPostcode = $addressChange['DPostcode'];
            $order->DCountryCode = $addressChange['DCountryCode'];
        }

        $shipper = $orderLibrary->getShipperData();
        $endpoint = 'https://onlinetools.ups.com';

        $params = [
            'paper_invoice' => false,
            'gif' => false,
        ];

        if (!$order) {
            return ['status' => false, 'message' => "Order with ID $id not found"];
        }

        if (
            ($order->ClientID == 49 || $order->ClientID == 50)
            && ($orderLibrary->isCI($order) && $orderLibrary->isCOD($order))
        ) {
            $shipperNumber = '97W57F';
            $fromName = 'TREATED.COM';
        } else {
            $shipperNumber = '97W57E';
            $fromName = 'HR HEALTHCARE';
        }

        $access = simplexml_load_file('xml_templates/ups_access.xml');
        $access->AccessLicenseNumber = 'ED405DEF7456FECD';
        $access->UserId = '97W57E';
        $access->Password = 'Liverpool1';

        if (\App::environment('local')) {
            $shipperNumber = 'E70E75';
            $endpoint = 'https://wwwcie.ups.com';
            $access = simplexml_load_file('xml_templates/ups_access_local.xml');
            $access->AccessLicenseNumber = '3CE4F60C9945D3CA';
            $access->UserId = 'natcoluk';
            $access->Password = 'eesa0786@';
        }


        if ($order->CountryCode == 1) {
            $serviceCode = '11';
            $serviceDescription = 'UPS Standard';
        } else {
            $serviceCode = '65';
            $serviceDescription = 'UPS Saver';
        }

        $url = '/ups.app/xml/ShipConfirm';

        //change access details
        $confirm = simplexml_load_file('xml_templates/ups_shipment_confirm.xml');
        $confirm->Request->TransactionReference->CustomerContext = $order->PrescriptionID . '-' . $order->ReferenceNumber;

        //shipper details
        $confirm->Shipment->Shipper->Name = 'Parcel Xpert'; //$shipper->TradingName;
        $confirm->Shipment->Shipper->AttentionName = 'Parcel Xpert'; //$shipper->TradingName;
        // $confirm->Shipment->Shipper->PhoneNumber = $shipper->Telephone;
        // $confirm->Shipment->Shipper->ShipperNumber = $shipperNumber;
        // $confirm->Shipment->Shipper->Address->AddressLine1 = $shipper->Address1 . ',' . $shipper->Address2;
        // $confirm->Shipment->Shipper->Address->City = $shipper->Address3;
        // $confirm->Shipment->Shipper->Address->StateProvinceCode = $shipper->Address4;
        // $confirm->Shipment->Shipper->Address->CountryCode = $shipper->CountryCodeName;
        // $confirm->Shipment->Shipper->Address->PostalCode = $shipper->Postcode;

        $confirm->Shipment->Shipper->PhoneNumber = $shipper->Telephone;
        $confirm->Shipment->Shipper->ShipperNumber = $shipperNumber;
        $confirm->Shipment->Shipper->Address->AddressLine1 = 'Unit 18' . ',' . 'Waters Meeting';
        $confirm->Shipment->Shipper->Address->City = 'Brittania way';
        $confirm->Shipment->Shipper->Address->StateProvinceCode = 'Bolton';
        $confirm->Shipment->Shipper->Address->CountryCode = 'GB';
        $confirm->Shipment->Shipper->Address->PostalCode = 'BL2 2HH';

        //clean mobile phone number
        $phone = preg_replace('/[^0-9]/', '', $order->Telephone);
        if ($phone == '') {
            $phone = preg_replace('/[^0-9]/', '', $order->Mobile);
        }

        //customer details
        $confirm->Shipment->ShipTo->CompanyName = $order->Name . ' ' . $order->Surname;
        $confirm->Shipment->ShipTo->AttentionName = $order->Name . ' ' . $order->Surname;
        $confirm->Shipment->ShipTo->PhoneNumber = $phone;
        $confirm->Shipment->ShipTo->Address->AddressLine1 = $order->DAddress1;
        $confirm->Shipment->ShipTo->Address->AddressLine2 = $order->DAddress2;
        $confirm->Shipment->ShipTo->Address->City = $order->DAddress3;
        $confirm->Shipment->ShipTo->Address->StateProvinceCode = '';
        $confirm->Shipment->ShipTo->Address->CountryCode = $order->CountryCodeName;
        $confirm->Shipment->ShipTo->Address->PostalCode = $order->DPostcode;

        if ($order->UPSAccessPointAddress == 1) {
            $alternate = $orderLibrary->getAlternateShipperData($id);
            $confirm->Shipment->AlternateDeliveryAddress->Name = $alternate->Name;
            $confirm->Shipment->AlternateDeliveryAddress->AttentionName = $alternate->Name;
            $confirm->Shipment->AlternateDeliveryAddress->UPSAccessPointID = $alternate->UPSAccessPoint;
            $confirm->Shipment->AlternateDeliveryAddress->Address->AddressLine1 = $alternate->Address1;
            $confirm->Shipment->AlternateDeliveryAddress->Address->AddressLine2 = $alternate->Address2;
            $confirm->Shipment->AlternateDeliveryAddress->Address->City = $alternate->Address3;
            $confirm->Shipment->AlternateDeliveryAddress->Address->StateProvinceCode = '';
            $confirm->Shipment->AlternateDeliveryAddress->Address->CountryCode = $alternate->CountryCodeName;
            $confirm->Shipment->AlternateDeliveryAddress->Address->PostalCode = $alternate->Postcode;
            $confirm->Shipment->ShipmentIndicationType->Code = '01';
        }

        if ($order->Email != '') {
            $confirm->Shipment->ShipmentServiceOptions->Notification->NotificationCode = '6';
            $confirm->Shipment->ShipmentServiceOptions->Notification->EMailMessage->FromName = $fromName;
            $confirm->Shipment->ShipmentServiceOptions->Notification->EMailMessage->EMailAddress = $order->Email;
            $confirm->Shipment->ShipmentServiceOptions->Notification->EMailMessage->Memo = 'PARCEL FROM ' . $fromName;
            $confirm->Shipment->ShipmentServiceOptions->Notification->Locale->Language = 'ENG';
            $confirm->Shipment->ShipmentServiceOptions->Notification->Locale->Dialect = 'GB';

            if ($order->UPSAccessPointAddress == 1) {
                $notification2 = $confirm->Shipment->ShipmentServiceOptions->addChild('Notification');
                $notification2->addChild('NotificationCode', '012');
                $email = $notification2->addChild('EMailMessage');
                $email->addChild('FromName', $fromName);
                $email->addChild('EMailAddress', $order->Email);
                $email->addChild('Memo', 'PARCEL FROM ' . $fromName);
                $locale = $notification2->addChild('Locale');
                $locale->addChild('Language', 'ENG');
                $locale->addChild('Dialect', 'GB');
            }
        }

        $currency = 'GBP';
        $products = null;
        $amount = 0;
        //special case for ci or cod orders
        if ($orderLibrary->isCI($order) || $orderLibrary->isCOD($order)) {
            $products = $orderLibrary->getProducts($id);

            if (count($products) == 0) {
                $orderLibrary->updateOrderMessage($id, 'No products found for order.');
                return ['status' => false, 'message' => "No products found for order"];
            }
            if ($order->Repeats != '0' && $order->Repeats != '') {
                $repeats = explode(' ', $order->Repeats);
                $amount = $repeats[1];
                $currency = $repeats[0];
            } else {
                $amount = 20;
            }

            $value = round($amount / count($products), 2);
        }

        //special case for ci orders
        if ($orderLibrary->isCI($order)) {
            // Possible Values are: 01 - Invoice; 03 - CO; 04 - NAFTA CO; 05 - Partial Invoice; 06 - Packinglist,
            // 07 - Customer Generated Forms; 08 – Air Freight Packing List; 09 - CN22 Form; 10 – UPS Premium Care Form,
            // 11 - EEI. For shipment with return service, 01, 05 or 10 are the only valid values.
            // Note: 01 and 05 are mutually exclusive and 05 are only valid for return shipments only.

            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->FormType = '01';
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->InvoiceNumber = $order->PrescriptionID . '-' . $order->ReferenceNumber; //Required for invoices
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->PurchaseOrderNumber = $order->PrescriptionID; //Required for invoices
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->InvoiceDate = date('Ymd'); //Required for invoices
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->DeclarationStatement = 'I hereby certify that the information on this invoice is true and correct and the contents and value of this shipment is as stated above.'; //Required for invoices
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->ReasonForExport = 'SALE'; //Required for invoices
            $confirm->Shipment->ShipmentServiceOptions->InternationalForms->CurrencyCode = $currency; //Required for invoices
            // $confirm->Shipment->ShipmentServiceOptions->InternationalForms->NumOfCopies = '01';//Number of pages

            $confirm->Shipment->SoldTo->CompanyName = $order->Name . ' ' . $order->Surname;
            $confirm->Shipment->SoldTo->AttentionName = $order->Name . ' ' . $order->Surname;
            $confirm->Shipment->SoldTo->PhoneNumber = $phone;
            $confirm->Shipment->SoldTo->Address->AddressLine1 = $order->DAddress1;;
            $confirm->Shipment->SoldTo->Address->AddressLine2 = $order->DAddress2;
            // $confirm->Shipment->SoldTo->Address->AddressLine3 = '';
            $confirm->Shipment->SoldTo->Address->City = $order->DAddress3;
            $confirm->Shipment->SoldTo->Address->StateProvinceCode = '';
            $confirm->Shipment->SoldTo->Address->PostalCode = $order->DPostcode;

            if ($order->CountryCodeName == 'IC') {
                $confirm->Shipment->SoldTo->Address->CountryCode = 'ES';
            } else {
                $confirm->Shipment->SoldTo->Address->CountryCode = $order->CountryCodeName;
            }

            //create Products in ShipmentServiceOptions
            if (!empty($products)) {
                foreach ($products as $product) {
                    $childProduct = $confirm->Shipment->ShipmentServiceOptions->InternationalForms->addChild('Product');
                    $childProduct->addChild('Description', substr($product->Description . ',' . $product->Quantity . 'x' . $product->Dosage . ' ' . $product->Unit, 0, 35));
                    $unit = $childProduct->addChild('Unit');
                    $unit->addChild('Number', 1);
                    $unit->addChild('Value', $value);
                    $unitOfMeasurement = $unit->addChild('UnitOfMeasurement');
                    $unitOfMeasurement->addChild('Code', 'CT');
                    $unitOfMeasurement->addChild('Description', 'Carton');

                    $childProduct->addChild('OriginCountryCode', 'GB');
                    $childProduct->addChild('CommodityCode', '30049000');
                    // $childProduct->addChild('CommodityCode', '');
                }
            }
        }

        if ($orderLibrary->isCOD($order)) {
            $codCode = 9;

            if ($order->PaymentMethod == '1') {
                $codCode = 1;
            }

            $confirm->Shipment->ShipmentServiceOptions->COD->CODCode = 3;
            $confirm->Shipment->ShipmentServiceOptions->COD->CODFundsCode = $codCode;
            $confirm->Shipment->ShipmentServiceOptions->COD->CODAmount->CurrencyCode = $currency;
            $confirm->Shipment->ShipmentServiceOptions->COD->CODAmount->MonetaryValue = $amount;
        }

        //service
        $confirm->Shipment->Service->Code = $serviceCode;
        $confirm->Shipment->Service->Description = $serviceDescription;
        //reference number
        $confirm->Shipment->ReferenceNumber->Value = $order->PrescriptionID . '-' . $order->ReferenceNumber;
        //payment information
        $confirm->Shipment->PaymentInformation->Prepaid->BillShipper->AccountNumber = $shipperNumber;
        //package
        $confirm->Shipment->Package->PackagingType->Code = '02';
        $confirm->Shipment->Package->PackageWeight->Weight = '0.5';

        //ShipmentConfirmRequest/LabelSpecification/LabelPrintMethod/Code
        $confirm->LabelSpecification->LabelPrintMethod->Code = 'ZPL';

        //combine the 2 xml files into 1
        $body = $access->asXML() . $confirm->asXML();

        $options = [
            'base_uri' => $endpoint,
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $body, //send it via body as xml
        ];

        if (isAzureStorageEnabled()) {
            Storage::disk('azure')->put('ups_xml/access-request-' . $id . '.xml', $body);
        } else {
            Storage::put('ups_xml/access-request-' . $id . '.xml', $body);
        }


        $client = new GuzzleHttp\Client($options);
        $response = $client->request('POST', $url, $options)->getBody()->getContents();

        $xml_response = simplexml_load_string($response);

        if (isAzureStorageEnabled()) {
            Storage::disk('azure')->put('ups_xml/access-response-' . $id . '.xml', $xml_response->asXML());
        } else {
            Storage::put('ups_xml/access-response-' . $id . '.xml', $xml_response->asXML());
        }


        if ($xml_response->Response->ResponseStatusCode != '1') {
            if (isset($xml_response->Error->ErrorDescription)) {
                $orderLibrary->updateOrderMessage($id, $xml_response->Error->ErrorDescription);
                return ['status' => false, 'message' => $xml_response->Error->ErrorDescription];
            } else if (isset($xml_response->Response->Error->ErrorDescription)) {
                $orderLibrary->updateOrderMessage($id, $xml_response->Response->Error->ErrorDescription);
                return ['status' => false, 'message' => $xml_response->Response->Error->ErrorDescription];
            } else {
                $orderLibrary->updateOrderMessage($id, 'Could Not Validate Address. Unknown Issue.');
                return ['status' => false, 'message' => 'Could Not Validate Address. Unknown Issue.'];
            }
        } else {
            $orderLibrary->updateOrderMessage($id, "Address Validated Successfully");
            return ['status' => true, 'message' => "Address Validated Successfully"];
        }
    }
}
