<?php

use App\Library\Doctor;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('base64Signature')) {
    /**
     * Get Base64 encoded signature image
     *
     * @param int $id doctor id
     * @param int $prescriptionID
     * @return string|false
     */
    function base64Signature($id, $prescriptionID)
    {
        $path = '';
        $extension = 'png';

        $doctor = new Doctor();

        $signature = $doctor->getSignature($id, $prescriptionID);

        if (isAzureStorageEnabled()) {
            if ($signature && Storage::disk('azure')->exists("signatures/$signature->Filename")) {
                $url = Storage::disk('azure')->url("signatures/$signature->Filename");
            } else if (Storage::disk('azure')->exists("signatures/DOC-$id.png")) {
                $path = Storage::disk('azure')->url("signatures/DOC-$id.png");
            } else if (Storage::disk('azure')->exists("/signatures/DOC-$id.jpg")) {
                $path = Storage::disk('azure')->url("signatures/DOC-$id.jpg");
                $extension = 'jpg';
            }
            if (!empty($url)) {
                return URLToBase64($url);
            }
        }

        if ($signature && Storage::exists("/signatures/$signature->Filename")) {
            $path = Storage::path("/signatures/$signature->Filename");
            $pathInfo = pathinfo($path);
            $extension = $pathInfo['extension'];
        } else {
            if (Storage::exists("/signatures/DOC-$id.png")) {
                $path = Storage::path("/signatures/DOC-$id.png");
            } else if (Storage::exists("/signatures/DOC-$id.jpg")) {
                $path = Storage::path("/signatures/DOC-$id.jpg");
                $extension = 'jpg';
            }
        }

        try {
            if (!empty($path)) {
                $img = file_get_contents($path);
                return "data:image/$extension;base64," . str_replace("\n", "", base64_encode($img));
            }
        } catch (\Throwable $th) {
            return false;
        }
        return false;
    }
}

if (!function_exists('sendResponse')) {
    /**
     * Success response method.
     *
     * @return JsonResponse
     */
    function sendResponse($result, $message = '', $success = true): JsonResponse
    {
        $response = [
            'success' => $success,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }
}

if (!function_exists('sendError')) {
    /**
     * Return error response.
     *
     * @return JsonResponse
     */
    function sendError($errors, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $errors,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('base64Image')) {
    function base64Image($path)
    {
        $extension = 'png';

        if (Storage::exists($path)) {
            $path = Storage::path($path);
        } else if (file_exists(public_path($path))) {
            $path = public_path($path);
        } else {
            return false;
        }

        $pathInfo = pathinfo($path);
        $extension = $pathInfo['extension'];
        $img = file_get_contents($path);
        return "data:image/$extension;base64," . str_replace("\n", "", base64_encode($img));
    }
}

if (!function_exists('findEmptyInArray')) {
    function findEmptyInArray($array)
    {
        $emptyList = [];
        if (!empty($array)) {
            foreach ($array as $k => $arr) {
                if (empty($arr)) {
                    $emptyList[] = $k;
                }
            }
        }
        return $emptyList;
    }
}

if (!function_exists('removeFromArray')) {
    function removeFromArray($array, $remove)
    {
        if (empty($array) || empty($remove)) {
            return [];
        }

        $newArr = [];

        foreach ($array as $arr) {
            if (!in_array($arr, $remove)) {
                $newArr[] = $arr;
            }
        }

        if (!empty($newArr)) {
            foreach ($remove as $r) {
                if (Str::contains($r, '.*')) {
                    $key = Str::remove('.*', $r);
                    foreach ($newArr as $k => $arr) {
                        if (Str::contains($arr, $key)) {
                            unset($newArr[$k]);
                        }
                    }
                }
            }
        }
        return $newArr;
    }
}

if (!function_exists('validateArrayWithRequired')) {
    /**
     * Validate array with required keys
     * @param array $list
     * @param array $required
     * @return array
     */
    function validateArrayWithRequired(array $list, array $required): array
    {
        $error = [];
        if (!empty($list)) {
            foreach ($list as $k => $val) {
                if (in_array($k, $required) && empty($val)) {
                    $error[] = $k;
                }
            }
        }
        return $error;
    }
}

if (!function_exists('moneyFormat')) {
    /**
     * Convert Amount to String with Currency
     * eg: 10 => $10 / 10 => £10
     *
     * @param mixed $amount
     * @param string $currency
     * @return string
     */
    function moneyFormat(mixed $amount, string $currency = ''): string
    {
        if (empty($currency)) {
            $currency = config('app.currency');
        }

        return $currency . $amount;
    }
}

if (!function_exists('getFileContents')) {
    /**
     * Wrapper for file_get_contents
     *
     * @param string $path
     * @return string | false
     */
    function getFileContents($path)
    {
        $opts = [
            "ssl" => [
                //"cafile" => __DIR__ . "/../../ssl/DigiCertGlobalRootCA.crt.pem",
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];

        return file_get_contents($path, false, stream_context_create($opts));
    }
}

if (!function_exists('downloadRemoteFile')) {
    /**
     * download remote file
     *
     * @param string $path
     * @param string $fileName
     * @param string $disk
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function downloadRemoteFile(string $path, string $fileName = '', string $disk = 'azure')
    {
        $url = Storage::disk($disk)->url($path);

        $fileName ??= basename($path);
        $tempImage = tempnam(sys_get_temp_dir(), $fileName);
        copy($url, $tempImage);

        return response()->download($tempImage, $fileName);
    }
}

if (!function_exists('URLToBase64')) {
    /**
     * convert URL to base64 string
     *
     * @param string $url
     * @return string
     */
    function URLToBase64($url)
    {
        $path = strtok($url, "?");
        $base64 = base64_encode(file_get_contents($url));
        $type = pathinfo($path, PATHINFO_EXTENSION);

        return 'data:image/' . $type . ';base64,' . $base64;
    }
}

if (!function_exists('saveToStorage')) {
    /**
     * Store file to Remote storage if configured otherwise store to local storage.
     *
     * @param string $filename
     * @param Illuminate\Http\File|Illuminate\Http\UploadedFile|Psr\Http\Message\StreamInterface|resource|string $contents,
     * @param string $filename name of file to store on server
     * @param string $disk default 'azure'
     * @return bool
     */
    function saveToStorage(string $path, $contents, string $filename, string $disk = 'azure')
    {
        $filePath = $path . $filename;
        if (isAzureStorageEnabled() && $disk == 'azure') {
            return Storage::disk('azure')->put($filePath, $contents);
        }
        return Storage::put($filePath, $contents);
    }
}

if (!function_exists('getXMLFromStorage')) {
    function getXMLFromStorage(string $filename, $disk = 'azure')
    {
        if (isAzureStorageEnabled() && $disk == 'azure') {
            $files = Storage::disk('azure')->allFiles('xml/');
            $matchFiles = collect(preg_grep("/{$filename}/i", $files));
            return $matchFiles->first();
        } else {
            $files = File::glob(storage_path() . "/app/public/xml/$filename*.xml");
            if (count($files) > 0) {
                return $files[0];
            }
        }
        return false;
    }
}

if (!function_exists('isAzureStorageEnabled')) {
    /**
     * Check if azure storage enabled or not
     *
     * @return boolean
     */
    function isAzureStorageEnabled()
    {
        return config('filesystems.disks.azure.key') != '' ? true : false;
    }
}


if (!function_exists('getEmailPhoneFromXML')) {    
    function getEmailPhoneFromXML($id){
        $data = [];
        $file = getXMLFromStorage($id);                
        if ($file) {
            try {
                if(isAzureStorageEnabled()){
                    $xmlContent = Storage::disk('azure')->get($file);
                }else{
                    $xmlContent = File::get($file);
                }

                $xml = simplexml_load_string($xmlContent);
                $data['email'] = (string)$xml->PatientDetail->Patient->Email;
                $data['Telephone'] = (string)$xml->PatientDetail->Patient->Telephone;
                $data['Mobile'] = (string)$xml->PatientDetail->Patient->Mobile;

                return $data;

            } catch (\Exception $e) {
                //Log::channel('daily')->error("Exception: " . $e->getMessage());
                return response()->json($e->getMessage());
            }
            
        }
    }
}