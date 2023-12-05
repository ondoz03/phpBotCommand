<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    function index()
    {
        $parameter = self::getData();
        $response = self::post_curl('sales-invoice/save.do', $parameter);
//        if ($response['code'] === 200) {
//            Log::build([
//                'driver' => 'daily',
//                'path' => storage_path('logs/custom.log'),
//            ])->info('Showing Status Created Successfully');
//        } else {
//            Log::build([
//                'driver' => 'daily',
//                'path' => storage_path('logs/custom.log'),
//            ])->info($response['message']);
//        }
        $data = [
            'par' =>$parameter,
            'response' => $response
        ];

        return  $data;
    }

    public function post_curl($url, $request = null, $isForm = false)
    {
        $curl = Http::withHeaders([
            'Authorization' => "Bearer b33f1903-485f-4013-b003-0a313237d0ae",
            'X-Session-ID' => '731d1922-2809-4143-938a-2c41e1155522'
        ])->withOptions(["verify" => false]);

        if ($isForm) {
            $curl = $curl->asForm();
        }

        $response = $curl->post("https://zeus.accurate.id" . "/accurate/api/" . $url, $request);



        return $response->json();

//        if ($response->status() >= 200 && $response->status() < 300) {
//            return ['message' => 'Successfuly', 'code' => $response->status(), 'data' => $response->json(), 'number' => $response];
//        } else if ($response->status() >= 400) {
//            return ['message' => 'Invalid token or session key not valid. Please go to setting menu and login to Accurate online for continue the proccess', 'code' => $response->status(), 'data' => $response->json()];
//        } else if ($response->status() === 400) {
//            return ['message' => 'Client error', 'code' => $response->status()];
//        } else if ($response->status() === 500) {
//            return ['message' => 'Server error', 'code' => $response->status()];
//        }
    }

    function getData()
    {
        $data = [
            "detailItem[0].itemNo" => self::item(),
            "detailItem[0].quantity" => $this->faker->randomDigit,
            "detailItem[0].unitPrice" => $this->faker->randomNumber(5),
            "detailItem[0].projectNo" => "",
            "detailItem[0].departmentName" => "",
            "detailItem[0].detailName" => $this->faker->text,
            "detailItem[0].salesmanListNumber[0]" => "4444",
            "detailItem[0].useTax1" => false,

            "taxable" =>false,

            "approvaStatus" => "APPROVED",
            "transDate" => date("d/m/Y"),
            "customerNo" => self::customerNo(),
            "branchId" => $this->faker->randomElement(["50"]),
            "currencyCode" => "IDR",
            "rate" => "0",
            "fiscalRate" => "0",
            "inclusiveTax" => false,
            "taxType" => "BKN_PEMUNGUT_PPN",
            "customerTaxType" => "BKN_PEMUNGUT_PPN",
            "documentCode" => "INVOICE",
            "poNumber" => "fasdfsdaf",
            "description" => $this->faker->text,
            "paymentTermName" => ""
        ];

        return $data;
    }

    function customerNo()
    {
        return $this->faker->randomElement([
            'CSBY-0005',
            'CJKT-0003',
            'CSBY-0011',
            'CJKT-0006',
            'CSBY-0003',
            'CJKT-0007',
            'CJKT-0004',
            'CSBY-0006',
            'CSBY-0007',
            'CSBY-0002',
            'CSBY-0010',
            'CJKT-0002',
            'CSBY-0008',
            'CSBY-0009',
            'CSBY-0012',
            'CJKT-0009',
            'CJKT-0008',
            'CJKT-0010',
            'CJKT-0001',
            'CSBY-0001',
            'CSBY-0004',
            'CJKT-0011',
            'CJKT-0005',
        ]);
    }

    function project()
    {

    }

    function item()
    {
        return $this->faker->randomElement(
            [
                '9900012',
                '9900013',
                '9900014',
                '9900015',
                '9900016',
                '9900006',
                '1300004',
            ]
        );
    }
}
