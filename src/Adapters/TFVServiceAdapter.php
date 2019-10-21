<?php

namespace src\Adapters;

use src\Contracts\Adapters\TFVServiceAdapter as TFVServiceAdapterContract;
use GuzzleHttp\Client;
Use DateTime;
use src\Helpers\excelArray;
use Sheets;
Use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;


class TFVServiceAdapter implements TFVServiceAdapterContract
{
    /**
     * Get bookings
     *
     * @return array
     */
    public function getBookings(){
        $client = new Google_Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\Users\deploy\Documents\test1\credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Drive::DRIVE);
        
        $driveService = new Google_Service_Drive($client);
        
        // List Files
        // $response = $driveService->files->listFiles();
        
        // Set File ID and get the contents of your Google Sheet
        $fileID = '1Mwv24ov1QT9H6i4Ba0hQ2TWqkazSYVClqNUeyC7LBXc';
        $response = $driveService->files->export($fileID, 'text/csv', array(
            'alt' => 'media'));
        
        $content = $response->getBody()->getContents();
        //dd($content);
        // Create CSV from String
        $csv = Reader::createFromString($content, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        
        // Create an Empty Array and Loop through the Records
        $newarray = array();
        foreach ($records as $value) {
        $newarray[] = $value;
        }
        
        // Dump and Die
        return $newarray;
    }

    /**
     * get translators
     *
     * @return array
     */
    public function getTranslators(){
        $client = new Google_Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\Users\deploy\Documents\test1\credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Drive::DRIVE);
        
        $driveService = new Google_Service_Drive($client);
        $fileID = '1Ay7vowAH9JwpWlyj-BAGa1nfBfCj1BA77dCEEeX-tSs';
        $response = $driveService->files->export($fileID, 'text/csv', array(
            'alt' => 'media'));
        
        $content = $response->getBody()->getContents();
        $csv = Reader::createFromString($content, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        
        // Create an Empty Array and Loop through the Records
        $newarray = array();
        foreach ($records as $value) {
        $newarray[] = $value;
        }
        
        // Dump and Die
        return $newarray;
    }
    public function getFormatDate($date){
        $date = substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2);
        return $date;
    }
    public function getFormatTime($date){
        $date = substr($date,0,2).':'.substr($date,2,2).':'.'00';
        return $date;
    }
    public function getData(){
        $Bookings=$this->getBookings();
        //return $Bookings;
        $i=0;
        foreach ($Bookings as $Book) {
            if ($Book['Type']=="Tfn"){
                $booking=new Booking($Book["id"],$Book["CustomerId"], "2019-07-13T19:00:42.411"/*$Book["startTime"]*/,$Book["Language"],"2019-07-13T19:09:42.411",$Book["Type"],$this->getFormatDate($Book['Date']),$this->getFormatTime($Book['StartTime']),$this->getFormatTime($Book['EndTime']));
                $BookingsOpta[]=$booking;
                $helperDistance=new HelperDistance($Book["id"],$Book["idTranslator"],$Book["distance"],$Book["eta"]);
                $Tran[]=$helperDistance;
                $i++;
                if($i==10000) break;
            }
            
        }
        $Translators=$this->getTranslators();
        foreach ($Translators as $trans) {
            $translator=new TfTranslator($trans["id"],$trans["ADRESS"],$trans["PADR"],5,$trans["Language"],$trans["Level"]);
            $translatorsOpta[]=$translator;
        }
    
        $Opta["com.bookingRoster"]["id"]="1";
        $Opta["com.bookingRoster"]["translators"]=$translatorsOpta;
       //$Opta["com.bookingRoster"]["translators"]=$translators;
        $Opta["com.bookingRoster"]["bookings"]=$BookingsOpta;
       //$Opta["com.bookingRoster"]["bookings"]=$Bookings;
        $Opta["com.bookingRoster"]["customTranslators"]=$Tran;
        // $Opta;
          

        $client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080']);
        $headers =  [
            'content-type'=>'application/json',
            'authorization' => 'Basic cGxhbm5lcjpQbGFubmVyMTIzXw==',
            'x-kie-contenttype'=>'json'
        ];


        $data = [
            'headers' => $headers,
            'json' => $Opta
        ];

        $response1 = $client->request('POST', '/kie-server/services/rest/server/containers/spread-TVF_1.0.0-SNAPSHOT/solvers/TranslatorSolver00/state/solving', $data);
        $url = 'http://localhost:8080/kie-server/services/rest/server/containers/spread-TVF_1.0.0-SNAPSHOT/solvers/TranslatorSolver00/bestsolution';
        $client = new \GuzzleHttp\Client(['headers' => ['content-type'=>'application/json',
        'authorization' => 'Basic cGxhbm5lcjpQbGFubmVyMTIzXw==',
        'x-kie-contenttype'=>'json'
        ]]);
        //send get request to fetch data
        sleep(900);
        $response = $client->request('GET', $url);
        
        $body = $response->getBody();
        $arr = json_decode($body,TRUE);
        $spreadsheet = new Spreadsheet();

        foreach ($arr["best-solution"]["com.bookingRoster"]["bookings"] as $Book) {
            $time = gmdate('d-m-Y', intval($Book["date"])/1000);
            $time = date('d-m-Y', strtotime($time)+60*60);
            $excelData[] = array("id"=>$Book["id"], "customerId"=>$Book["customerId"], "language"=>$Book["language"],"type"=>$Book["type"],"date"=>$time,"startTime"=>implode(":",$Book["start"]),"endTime"=>implode(":",$Book["endT"]),"translator"=>$Book["translator"]["id"],"Level"=>$Book["translator"]["level"]);
        }
           $spreadsheet->getActiveSheet()
            ->fromArray(
                $excelData,
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            );
        
        // Create Excel file and sve in your directory
        $writer = new Xlsx($spreadsheet);
        $writer->save(__DIR__ . '/mysheet.xlsx');
        $solved=$arr["best-solution"]["com.bookingRoster"]["bookings"];
        return $solved;
        
    }
}