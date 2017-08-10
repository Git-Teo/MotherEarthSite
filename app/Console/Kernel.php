<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Soap\CLFSoapClient;
use App\Http\Controllers\SoapController;
use Artisaninweb\SoapWrapper\SoapWrapper;
use DOMDocument;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $sw = new SoapWrapper;
            $sc = new SoapController($sw);
            $dom=new DOMDocument();

            $updatedskusxml = $sc->getProductCodes();
            $dom->loadXML($updatedskusxml);
            $root=$dom->documentElement;
            $data=$root->getElementsByTagName('Code');
            $updatedskus = [];

            foreach ($data as $child) {
                array_push($updatedskus, $child->getElementsByTagName('sku')->item(0)->textContent);
            }

            $currentskus = [];
            $skus = DB::select('select sku from products');

            foreach ($skus as $sku) {
                array_push($currentskus, $sku->sku);
            }
            
            $newskus = array_diff($updatedskus, $currentskus);



        })->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
