<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    private $faker;

    public function __construct(Faker $faker) {
        $this->faker = $faker;
    }

    function index() {
        $parameter = self::getData();
        $response = self::post_curl( 'sales-invoice/save.do',$parameter);
        if($response['code'] === 200){
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/custom.log'),
            ])->info('Showing Status Created Successfully');
        }else{
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/custom.log'),
            ])->info($response['message']);
        }

        return $response['number'];
    }

    public function post_curl($url, $request = null, $isForm = false)
    {
        $curl = Http::withHeaders([
            'Authorization' => "Bearer 74980f40-1082-4226-8102-c2480bf7c5c3",
            'X-Session-ID' => 'e0456b57-20ef-4ec7-819c-73b97729a049'
        ])->withOptions(["verify" => false]);

        if ($isForm) {
            $curl = $curl->asForm();
        }

        $response = $curl->post("https://pvsh13.pvt1.accurate.id" . "/accurate/api/" . $url, $request);


        if ($response->status() >= 200 && $response->status() < 300) {
            return  ['message' => 'Successfuly', 'code' => $response->status(), 'data' => $response->json() ,'number' => $response['r']['number']];
        } else if ($response->status() >= 400) {
            return  ['message' => 'Invalid token or session key not valid. Please go to setting menu and login to Accurate online for continue the proccess', 'code' => $response->status(), 'data' => $response->json()];
        } else if ($response->status() === 400) {
            return  ['message' => 'Client error', 'code' => $response->status()];
        } else if ($response->status() === 500) {
            return  ['message' => 'Server error', 'code' => $response->status()];
        }
    }

    function getData()
    {
        $data = [
            "detailItem[0].itemNo"=> self::item(),
            "detailItem[0].quantity"=> $this->faker->randomDigit,
            "detailItem[0].unitPrice"=> $this->faker->randomNumber(5),
            "detailItem[0].projectNo"=> "",
            "detailItem[0].departmentName"=> "",
            "detailItem[0].detailName"=> $this->faker->text,
            "detailItem[0].salesmanListNumber[0]"=> "4444",
            "detailItem[0].useTax1"=>  $this->faker->randomElement(['true','false']),

            "taxable" => $this->faker->randomElement(['true','false']),

            "approvaStatus" => "APPROVED",
            "transDate" => date("d/m/Y"),
            "customerNo" => self::customerNo(),
            "branchId" =>  $this->faker->randomElement(["50"]),
            "currencyCode" => "IDR",
            "rate" => "0",
            "fiscalRate" => "0",
            "inclusiveTax" => $this->faker->randomElement(['true','false']),
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
        "C.00001",
        "C.00002",
        "C.00003",
        "C.00004",
        "C.00005",
        ]);
    }

    function project()
    {

    }

    function item()
    {
        return $this->faker->randomElement(
            [
                "100006",
                "100007",
                "100008",
                "100009",
                "100010",
            ]
        );
    }
}
