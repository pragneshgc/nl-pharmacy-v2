<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ClientShipping;
use Illuminate\Http\JsonResponse;
use App\Library\Client as ClientLib;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateClientRequest;

class ClientController extends Controller
{
    private $client;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->client = new ClientLib();
    }

    /**
     * Get a list of all the clients
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->client->getClientsPaginated($this->q, $this->s, $this->o);
        $data = $this->client->setSearchParameters($this->f, $request, $data)->paginate($this->l);

        return $this->sendResponse($data);
    }

    /**
     * Deactivate a client
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deactivate($id)
    {
        return $this->sendResponse($this->client->deactivate($id));
    }

    /**
     * Delete a client
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        return $this->sendResponse($this->client->delete($id));
    }

    /**
     * Get details of a client with ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function client($id)
    {
        //return $this->sendResponse($this->client->getClient($id));
        if (Auth::user()->role == 60) {
            $client = collect(Client::with('shipping')->find($id));
        } else {
            $client = collect(Client::find($id));
        }

        return $this->sendResponse($client);
    }

    /**
     * Update client via ID
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        $data = $request->validate([
            'Title' => 'nullable|max:255',
            'Name' => 'sometimes|nullable|max:255',
            'Middlename' => 'nullable',
            'Surname' => 'sometimes|max:255',
            'CompanyName' => 'nullable|max:255',
            'Address1' => 'required|max:255',
            'Address2' => 'nullable|max:255',
            'Address3' => 'nullable|max:255',
            'Address4' => 'nullable|max:255',
            'Postcode' => 'required|max:255',
            'CountryID' => 'required',
            'Telephone' => 'nullable',
            'Mobile' => 'nullable',
            'Email' => 'nullable',
            'InvoiceEmail' => 'nullable',
            'CreditLimit' => 'required',
            'DispensingFee' => 'required',
            'IP' => 'sometimes',
            'Type' => 'required',
            'Status' => 'required',
            'Notes' => 'nullable',
            'CompanyNumber' => 'nullable',
            'GPHCNO' => 'nullable',
            'ReturnURL' => 'nullable',
            'Username' => 'required',
            'Password' => 'required',
            'APIKey' => 'required',
            'ITName' => 'required',
            'ITSurname' => 'nullable',
            'ITEmail' => 'required',
            'TradingName' => 'required',
            'AdditionalComment' => 'nullable',
            'ReturnUsername' => 'nullable',
            'ReturnPassword' => 'nullable',
            'PendingPharmacyURL' => 'nullable',
            'PendingPharmacyEndpoint' => 'nullable',
            'VAT' => 'required',
            'DisplayVAT' => 'nullable',
        ]);

        $client = Client::findOrFail($id);
        $response = $client->update($data);
        if (Auth::user()->role == 60) {
            if (
                $request['delivery_api']['ups']['endpoint'] != ''
                || $request['delivery_api']['dpd']['endpoint'] != ''
                || $request['delivery_api']['rml']['endpoint'] != ''
                || $request['delivery_api']['dhl']['shipper_name'] != ''
            ) {
                $response = ClientShipping::updateOrCreate(
                    [
                        'client_id' => $id
                    ],
                    [
                        'content' => $request['delivery_api']
                    ]
                );
            } else {
                ClientShipping::where('client_id', $id)->delete();
            }
        }

        return $this->sendResponse($response, 'Client updated');
    }

    /**
     * Insert a new client
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function insert(CreateClientRequest $request)
    {
        $data = $request->validated();

        $data['APIKey'] = md5($data['Username']);
        $data['Type'] ??= 2;
        $data['Status'] ??= 1;

        return $this->sendResponse(Client::create($data));
    }
}
