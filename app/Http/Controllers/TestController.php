<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Library\Order;
use GuzzleHttp\Client;
use App\Library\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use SoapClient;
use SOAPHeader;

class TestController extends Controller
{
    private $regions = ['1' => 'AF', '2' => 'AM', '3' => 'AM', '4' => 'AN', '5' => 'AP', '6' => 'EU', '7' => 'AU'];
    public function index($start, $end)
    {
        $result = DB::table('invoiceitem AS ii')
            ->select('ii.PrescriptionID as pid', 'Prescription.PrescriptionID')
            ->rightJoin('Prescription', 'Prescription.PrescriptionID', '=', 'ii.PrescriptionID')
            ->whereNull('ii.PrescriptionID')
            ->where("Prescription.Status", 8)
            ->when($start != '', function ($sql) use ($start) {
                $sdate = new \DateTime($start);
                $sdate->setTime(00, 00, 00);
                $start_date = $sdate->getTimestamp();
                return $sql->where("Prescription.UpdatedDate", '>', $start_date);
            })
            ->when($end != '', function ($sql) use ($end) {
                $edate = new \DateTime($end);
                $edate->setTime(23, 59, 59);
                $end_date = $edate->getTimestamp();
                return $sql->where("Prescription.UpdatedDate", '<', $end_date);
            })
            ->groupBy('Prescription.PrescriptionID')
            ->get();

        if ($result->isNotEmpty()) {
            foreach ($result as $res) {
                echo "Generating invoice for $res->PrescriptionID" . PHP_EOL;
                $this->generateInvoice($res->PrescriptionID);
            }
        } else {
            echo "No Data found";
        }
    }

    private function generateInvoice($id)
    {
        $invoice = new Invoice();

        if ($invoice->generate($id)) {
            echo "Generated invoice for $id" . PHP_EOL;
        } else {
            echo "Fail to generate invoice for $id" . PHP_EOL;
        }
    }

    public function uploadForm()
    {
        $img = Storage::disk('azure')->url('signatures/DOC-4.png');
        return view('azure', compact('img'));
    }

    public function azureUpload(Request $request)
    {
        if ($request->file()) {
            $fileName = $request->file->getClientOriginalName();
            try {
                $filePath = $request->file('file')->storeAs('/pdf', $fileName, 'azure');
            } catch (ServiceException $e) {
                echo '<pre>';
                print_r($e->getMessage());
                print_r($e->getFile());
                print_r($e->getLine());
                print_r($e->getCode());
                echo '</pre>';
                exit;
            } catch (Exception $ex) {
                echo '<pre>';
                print_r($ex->getMessage());
                print_r($ex->getFile());
                print_r($ex->getLine());
                echo '</pre>';
                exit;
            }

            echo "file uploaded to: " . $filePath;
            //return back()->with('success', 'File has been uploaded to ' . $filePath);
        } else {
            return back()
                ->with('error', 'Something went wrong.');
        }
    }

    public function removeFile($id)
    {
        //if (Storage::disk('azure')->exists('/signatures/DOC-1-1697180303.png/jS28L1vo6ymgxEzNF8yqEM0ZA7WItDrXUJAZ13rF.png')) {
        $url = Storage::disk('azure')->deleteDirectory('signatures/DOC-1-1697180303.png');
        echo $url;
        //}
    }

    public function downloadXML($id)
    {
        $prescription = DB::table('Prescription')->where('PrescriptionID', $id)->first();

        $filename = $prescription->PrescriptionID . '-Ref-' . $prescription->ReferenceNumber . '.xml';

        //find file
        $files = Storage::disk('azure')->allFiles('xml/');

        $match = $prescription->PrescriptionID;
        $matchFiles = collect(preg_grep("/{$match}/i", $files));

        if ($matchFiles->isEmpty()) {
            return $this->sendError("Failed to fetch order email", ['Failed to fetch order email']);
        } else {
            return downloadRemoteFile($matchFiles->first(), $filename);
        }
    }

    public function dhltest()
    {
        $data = $this->shipmentData();
        $body = view('xml.dhl.test_data')->with(compact('data'))->render();

        $url = "https://xmlpitest-ea.dhl.com/XMLShippingServlet";

        $options = [
            'base_uri' => "https://xmlpitest-ea.dhl.com",
            'headers' => [
                'Content-Type' => 'application/xml; charset=UTF8',
            ],
            'body' => '<?xml version="1.0" encoding="UTF-8"?>' . $body
        ];

        $client = new Client($options);
        $response = $client->request('POST', $url, $options)->getBody()->getContents();

        $xml = simplexml_load_string($response);
        $id = time();
        $filename = "validation-response-test-" . time() . ".xml";
        Storage::put($filename, $xml->asXML());

        Storage::put('label-' . $id . '.pdf', base64_decode($xml->LabelImage->OutputImage));
        Storage::put('invoice-' . $id . '.pdf', base64_decode(strip_tags($xml->LabelImage->MultiLabels->MultiLabel->DocImageVal)));
    }

    private function shipmentData()
    {
        $date = Carbon::now()->format('c');
        $shipment_date = Carbon::now()->addDay()->format('Y-m-d');
        $invoice_date = Carbon::now()->format('Y-m-d');
        $url = Storage::disk('azure')->url('sign.png');
        $signature = base64_encode(file_get_contents($url));

        $orderLib = new Order;

        $shipper = $orderLib->getShipperData();
        // echo "<pre>";
        // print_r($shipper);
        // exit;

        $regionCode = $this->regions[$shipper->RegionID];
        $accountNumber = '425978591';
        $id = '126165';
        $order = $orderLib->getOrderDetails($id);       
        $shipper = $orderLib->getShipperData();
        $product = $orderLib->getProduct($id);

        $vat = 'GB423627604';

        $logourl = Storage::disk('azure')->url('logo.png');
        $logo = base64_encode(file_get_contents($logourl));

        return [
            'date' => $date,
            'shipment_date' => $shipment_date,
            'invoice_date' => $invoice_date,
            'signature' => $signature,
            'regionCode' => $regionCode,
            'accountNumber' => $accountNumber,
            'order' => $order,
            'shipper' => $shipper,
            'vat' => $vat,
            'product' => $product,
            'logo' => $logo
        ];
    }

    public function dpdtest(){

        
        $c = new SoapClient('https://public-ws-stage.dpd.com/services/LoginService/V2_0/?wsdl');

        $res = $c->getAuth(array(
          'delisId'          => 'sandboxdpd',
          'password'         => 'xMmshh1',
          'messageLanguage'  => 'en_EN'
        ));
        
        $auth = $res->return;
        
        $c = new SoapClient('https://public-ws-stage.dpd.com/services/ShipmentService/V4_4/?wsdl');

        $token = array(
          'delisId'         => $auth->delisId,
          'authToken'       => $auth->authToken,
          'messageLanguage' => 'en_EN'
        );

        // Set the header with the authentication token
        $header = new SOAPHeader('http://dpd.com/common/service/types/Authentication/2.0', 'authentication', $token);
        $c->__setSoapHeaders($header);
        try {
            $res = $c->storeOrders(array(
                "printOptions" => array(
                    "printOption" => array(
                        "outputFormat" => "PDF",
                        "paperFormat" => "A4",
                        "printerLanguage" => "PDF"
                    )
                ),

                "order" => array(
                    "generalShipmentData" => array(
                        "sendingDepot" => $auth->depot,
                        "product" => "CL",
                        "mpsCompleteDelivery" => false,
                        "sender" => array(
                            "name1" => "Sender Name",
                            "street" => "Sender Street 2",
                            "country" => "DE",
                            "zipCode" => "65189",
                            "city" => "Wiesbaden",
                            "customerNumber" => "123456789"
                        ),
                        "recipient" => array(
                            "name1" => "John Malone",
                            "street" => "Johns Street 34",
                            "country" => "DE",
                            "zipCode" => "65201",
                            "city" => "Wiesbaden"
                        )
                    ),
                    "parcels" => array(
                        "parcelLabelNumber" => "09123829120"
                    ),
                    "productAndServiceData" => array(
                        "orderType" => "consignment"
                    )
                )
            ));
                     
            if (isset($res->orderResult)) {
              
               header('Content-type: application/pdf');
               echo $res->orderResult->output->content;
               
               Storage::put('dpd-label-' . date("Ymdhis") . '.pdf', $res->orderResult->output->content);
               echo "SUCCESS: 'parcellabels' has been generate";
            } else {
                echo "ERROR: 'parcellabelsPDF' property not found in the response.";
            }
        } catch (SoapFault $exception) {
            echo $exception->getMessage();
            die();
        }
       
    }
}
