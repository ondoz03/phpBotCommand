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

        return $response;
    }

    public function post_curl($url, $request = null, $isForm = false)
    {
        $curl = Http::withHeaders([
            'Authorization' => "Bearer e451d06b-520b-4a6b-b8e6-ea6a7355a4e7",
            'X-Session-ID' => 'b6fc30af-d977-4830-8665-574c323f9288'
        ])->withOptions(["verify" => false]);

        if ($isForm) {
            $curl = $curl->asForm();
        }

        $response = $curl->post("https://v6lp64.pvt1.accurate.id" . "/accurate/api/" . $url, $request);


        if ($response->status() >= 200 && $response->status() < 300) {
            return  ['message' => 'Successfuly', 'code' => $response->status(), 'data' => $response->json()];
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
            "detailItem[0].projectNo"=> self::project(),
            "detailItem[0].departmentName"=> "",
            "detailItem[0].detailName"=> $this->faker->text,
            "detailItem[0].salesmanListNumber[0]"=> "4444",
            "detailItem[0].useTax1"=>  $this->faker->randomElement(['true','false']),


            "taxable" => $this->faker->randomElement(['true','false']),
            "number" => "PI.JKT/2023/" . date("d-m-Y") . "/1",
            "approvaStatus" => "APPROVED",
            "transDate" => date("d/m/Y"),
            "customerNo" => self::customerNo(),
            "branchId" =>  $this->faker->randomElement(["100", "50" ,"203","4155"]),
            "currencyCode" => "IDR",
            "rate" => "0",
            "fiscalRate" => "0",
            "inclusiveTax" => $this->faker->randomElement(['true','false']),
            "taxType" => "BKN_PEMUNGUT_PPN",
            "customerTaxType" => "BKN_PEMUNGUT_PPN",
            "documentCode" => "INVOICE",
            "poNumber" => "fasdfsdaf",
            "description" => $this->faker->text,
            "paymentTermName" => $this->faker->randomElement(["net 10" , "net 15" , "net 20" , "net 21" , "net 30"])
        ];

        return $data;
    }

    function customerNo()
    {
        return $this->faker->randomElement([
        "CJ0549",
        "CUSTOMER JKTA",
        "CJ0546",
        "CJ0548",
        "CUST/0683",
        "CUST/2208/00191",
        "CUST/0628",
        "CJ0547",
        "CJ0545",
        "CUST/2110/00102",
        "CUST/0034",
        "CUST/0381",
        "CUST/0501",
        "CS0103",
        "CUST/0654",
        "CS0101",
        "CJ0001",
        "CJ000299",
        "CJ0003",
        "CJ00048",
        ]);
    }

    function project()
    {
        return $this->faker->randomElement([
            "JOB_TST/1100075",
            "JOB_FWD/00122",
            "JOB_FWD/00110",
        ]);
    }

    function item()
    {
        return $this->faker->randomElement(
            [
                "666",
                "100035",
                "JIF007",
                "JIT004",
                "JIT004.1",
                "SIF007.1",
                "JIF007.1",
                "SIF007",
                "JIF023.1",
                "SIF015",
                "SIF015.1",
                "JIF023",
                "INTS007.1",
                "INTS007",
                "JIF014",
                "JIF014.1",
                "JIF040.1",
                "JIF040",
                "JIF022.1",
                "JIF022",

            ]
        );
    }
}
