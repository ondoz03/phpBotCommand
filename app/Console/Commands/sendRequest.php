<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Log;

class SendRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $faker;

    public function __construct(Faker $faker)
    {
        parent::__construct();
        $this->faker = $faker;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $parameter = self::getData();
        $response = self::post_curl( 'sales-invoice/save.do',$parameter);

        if($response['code'] === 200){
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/custom.log'),

            ])->info('Showing Status Created Successfully data dengan number ' . $response['number']);

            $this->info('Showing Status Created Successfully data ke dengan number ' . $response['number']);
        }else{
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/custom.log'),
            ])->info($response['message']);
            $this->info($response['message']);
        }

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
            "detailItem[0].useTax1"=>  false,
            "taxable" => 'false',
            "approvaStatus" => "APPROVED",
            "transDate" => date("d/m/Y"),
            "customerNo" => self::customerNo(),
            "branchId" =>  $this->faker->randomElement(["50"]),
            "currencyCode" => "IDR",
            "rate" => "0",
            "fiscalRate" => "0",
            "inclusiveTax" => 'false',
            "taxType" => "BKN_PEMUNGUT_PPN",
            "customerTaxType" => "BKN_PEMUNGUT_PPN",
            "documentCode" => "INVOICE",
            "poNumber" => "fasdfsdaf",
            "description" => $this->faker->text,
            "paymentTermName" => ''
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
                '9900007',
                '9900008',
                '9900004',
                '9900005',
                '8800002',
                '100003',
                '9900009',
                '8800003',
                '9900002',
                '9900001',
                '5116001',
                '5132002',
                '5164003',
                '5216001',
                '5232002',
                '5264003',
                '6112803',
                '6116001',
                '6164002',
                '6212803',
                '6216001',
                '6264002',
                '6312803',
                '6316001',
                '6364002',
                '100002',
                '100001',
                '9800001',
                '9800002',
                '8800001',
                '9900018',
                '9900017',
                '1200002',
                '1200001',
                '1200003',
                '1200004',
                '9900019',
                '9900021',
                '9900020',
                '9900010',
                '1100005',
                '1100006',
                '1100003',
                '1100007',
                '1100008',
                '1100001',
                '1100002',
                '1100004',
                '9900003',
                '8800005',
                '9900011',
                '8800004',
                '1300003',
                '1300002',
                '1300005',
                '1300001',
                '1300004',
            ]
        );
    }
}
