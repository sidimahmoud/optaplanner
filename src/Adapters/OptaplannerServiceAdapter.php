<?php

namespace src\Adapters;

use src\Contracts\Adapters\OptaplannerServiceAdapter as OptaplannerServiceAdapterContract;
use GuzzleHttp\Client;
Use DateTime;
use src\Helpers\excelArray;
use Sheets;
Use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use src\Helpers\Booking;
use src\Helpers\DtBooking;
use src\Helpers\Translator;
use src\Helpers\customTranslators;
use src\Helpers\unvailable;

class OptaplannerServiceAdapter implements OptaplannerServiceAdapterContract
{
    /**
     * Get bookings
     *
     * @return array
     */
    public function getBookings(){
        $url1 = 'https://api-gateway.digitaltolk.se/api/v3/bookings?filter[type]=phone&filter[from_language_id]=3&all=true&include=alternative_languages%2Ccustomer%2Cspecific_translators%2Cspecific_translators.translator&sort=-created_at&filter%5Bis_test%5D=0&filter%5Bdate_range%5D=due%2C2019-09-17%2000%3A00%3A00%2C2019-09-30%2023%3A59%3A50';
        $client1 = new Client(['headers' => ['accept'=>'application/json',
        'Authorization' => 'Bearer '.env('DT_KEY')
        ]]);
        //send get request to fetch data
        $response1 = $client1->request('GET', $url1);
        //check response status ex: 200 is 'OK'
        if ($response1->getStatusCode() == 200) {
            //header information contains detail information about the response.
            if ($response1->hasHeader('Content-Length')) {
                //get number of bytes received
                $content_length = $response1->getHeader('Content-Length')[0];
            }
            //get body content
            $body1 = $response1->getBody();
            $content1 =$body1->getContents();
            $arr1 = json_decode($content1,TRUE);
            return $arr1;
        }
    }

    /**
     * get translators
     *
     * @return array
     */
    public function getTranslators(){
        $url = 'https://api-gateway.dev.digitaltolk.com/api/v3/translators?filter[employee]=1&include=languages,translator_data,translator_data.employee_working_hours';
        $client = new Client(['headers' => ['accept'=>'application/json',
        'Authorization' => 'Bearer '.env('DT_KEY')
        ]]);
        //send get request to fetch data
        $response = $client->request('GET', $url);
        //check response status ex: 200 is 'OK'
        if ($response->getStatusCode() == 200) {
            //header information contains detail information about the response.
            if ($response->hasHeader('Content-Length')) {
                //get number of bytes received
                $content_length = $response->getHeader('Content-Length')[0];
                
            }
            //get body content
            $body = $response->getBody();
            $arr = json_decode($body,TRUE);
            return $arr;
           
        }
    }

    /**
     * get translators unavailable times
     *
     * @return array
     */
    public function getTranslatorUnavailableTimes(){
        $url = 'https://api-gateway.dev.digitaltolk.com/api/v3/translator-unavailable-times';
        $client = new Client(['headers' => ['accept'=>'application/json',
        'Authorization' => 'Bearer '.env('DT_KEY')
        ]]);
        //send get request to fetch data
        $response = $client->request('GET', $url);
        //check response status ex: 200 is 'OK'
        if ($response->getStatusCode() == 200) {
            if ($response->hasHeader('Content-Length')) {
                $content_length = $response->getHeader('Content-Length')[0];
            }
            //get body content
            $body = $response->getBody();
            $arr = json_decode($body,TRUE);
            return $arr; 
        }
    }
    /**
     * get translators unavailable times
     *
     * @return array
     */
    public function getDistancesTime(){
        $custom=[];
      foreach($bookingsData as $data){
        //   dd($data);
            $url3 = 'https://api-gateway.digitaltolk.se/api/v3/bookings/' . $data['id'] . '/batches?include=entries.translator';
            $client3 = new Client(['headers' => ['accept'=>'application/json',
            'Authorization' => 'Bearer '.env('DT_KEY')
            ]]);
            //send get request to fetch data
            $response3 = $client3->request('GET', $url3);
            //check response status ex: 200 is 'OK'
            if ($response3->getStatusCode() == 200) {
                //header information contains detail information about the response.
                if ($response3->hasHeader('Content-Length')) {
                    //get number of bytes received
                    $content_length3 = $response3->getHeader('Content-Length')[0];
                }
                //get body content
                $body3 = $response3->getBody();
                $content3 =$body3->getContents();
                $arr3 = json_decode($content3,TRUE);
                $distance[]=$arr3["data"]["batches"];    
            } 
      }
        foreach($distance as $dis){
            foreach($dis as $dis1){
                $custom[]=new customTranslators($dis1['entries'][0]["booking_id"],$dis1['entries'][0]["translator_id"],$dis1['entries'][0]["temp_travel_time_public"],$dis1['entries'][0]["temp_travel_time_car"],$dis1['entries'][0]["temp_travel_distance_public"],$dis1['entries'][0]["temp_travel_distance_car"]);
            }
        }
    return $custom;
  }
  /**
     * get optaplanner data
     *
     * @return array
     */
    public function getData(){
        $translators=[];
        $BookingsOpta=[];
        $Bookings=$this->getBookings();
        return $Bookings;
        foreach ($Bookings["data"]["bookings"] as $Book) {
            //if($Book["due"]!=""){
            $send = date('Y-m-d H:i:s',strtotime('+'.$Book["duration"].' minutes',strtotime($Book["due"])));;
            //echo $Book["due"].'    '.$start.'<br/>';
            $booking=new DtBooking($Book["id"],$Book["customer_id"],str_replace(" ","T",$send)/*.".526+05:30"/*"2017-09-15T13:50:30.526+05:30"*/,/*2019-07-13 19:00:42.411,*/$Book["from_language_id"],/*"2017-09-15T13:50:30.526+05:30"*/str_replace(" ","T",$Book["due"])/*.".526+05:30"*/,/*2019-07-13 19:09:42.411,*/$Book["type"]);
            $BookingsOpta[]=$booking;
            ///echo str_replace(" ","T",$Book["cancelled_at"]).".526+05:30"."<br/>";
        //}
        }
        //return $BookingsOpta;

        $translatorsOpta=[];
        $Translators=$this->getTranslators();
        //return $Translators["data"]["translators"];
        // info('trans', [$Translators]);
        $workHours[]=array("id"=>"1",
                             "translator_data_id"=>"100",
                                "time_from"=> "08:00:00",
                                "time_end"=> "12:00:00");
        $workHours[]=array(
            "id"=>"2",
            "translator_data_id"=>"100",
               "time_from"=> "14:00:00",
               "time_end"=> "17:00:00"
        );
        //return $workHours;
        foreach ($Translators["data"]["translators"] as $trans) {

           if(!empty($trans["translator_data"]["employee_working_hours"]) ){
           $translator=new Translator($trans["id"],'ADRESS','fdsfds',$trans["type"],"Ludhianiska",$trans["type"]/*,"lan"*/,$trans["languages"],"14:00:00","16:00:00","1","13:00:00","60",$trans["translator_data"]["employee_working_hours"],$trans["translator_data"]["lunch_time_fixed_switch"],$trans["translator_data"]["lunch_time_range_switch"],"12:30:00","14:00:00"/*$trans["translator_data"]["lunch_time_from"],$trans["translator_data"]["lunch_time_to"]*/);            
           }else{
           $translator=new Translator($trans["id"],'ADRESS','fdsfds',$trans["type"],"Ludhianiska",$trans["type"]/*,"lan"*/,$trans["languages"],"14:00:00","16:00:00","1","13:00:00","60",$workHours,$trans["translator_data"]["lunch_time_fixed_switch"],$trans["translator_data"]["lunch_time_range_switch"],"12:30:00","14:00:00"/*$trans["translator_data"]["lunch_time_from"],$trans["translator_data"]["lunch_time_to"]*/);
           }
            $translatorsOpta[]=$translator;
        }

        //return $translatorsOpta;

        $unvailableTime=[];
        $unvailableObject=$this->getTranslatorsUnvailableTimes();
        //return $unvailableObject;
        foreach ($unvailableObject["data"]["translator_unvailable_times"] as $trans) {
            $translator=new unvailable($trans["id"],$trans["translator_id"],$trans["description"],$trans["unavailable_to"],$trans["address"],str_replace(" ","T",$trans["unavailable_from"]),str_replace(" ","T",$trans["unavailable_until"]),$trans["created_at"],$trans["updated_at"]);
            $unvailableTime[]=$translator;
        }
        
        $Opta["com.bookingRoster"]["id"]="1";
        $Opta["com.bookingRoster"]["translators"]=$translatorsOpta;
        $Opta["com.bookingRoster"]["bookings"]=$BookingsOpta;
        $Opta["com.bookingRoster"]["translatorUnvailableTimes"]=$unvailableTime;
        
         //return $Opta;
        $client = new Client(['base_uri' => 'http://localhost:8080']);
        $headers =  [
            'content-type'=>'application/json',
            'authorization' => 'Basic cGxhbm5lcjpQbGFubmVyMTIzXw==',
            'x-kie-contenttype'=>'json'
        ];
        $data = [
            'headers' => $headers,
            'json' => $Opta
        ];
        $response1 = $client->request('POST', '/kie-server/services/rest/server/containers/Tfv_project_V1_1.0.0-SNAPSHOT/solvers/TranslatorSolver03/state/solving', $data);
        
        
        $url = 'http://localhost:8080/kie-server/services/rest/server/containers/Tfv_project_V1_1.0.0-SNAPSHOT/solvers/TranslatorSolver03/bestsolution';
        $client = new Client(['headers' => ['application/json',
        'authorization' => 'Basic cGxhbm5lcjpQbGFubmVyMTIzXw==',
        'x-kie-contenttype'=>'json'
        ]]);
        //send get request to fetch data
        sleep(200);
        $response = $client->request('GET', $url);
        
        $body = $response->getBody();
        $arr = json_decode($body,TRUE);
        //return $arr;
        $spreadsheet = new Spreadsheet();

        foreach ($arr["best-solution"]["com.bookingRoster"]["bookings"] as $Book) {
            $time =$this->toDate($Book["startTime"]);
            $time1 = $this->toDate($Book["endTime"]);
        $excelData[] = array("id"=>$Book["id"], "customerId"=>$Book["customerId"], "language"=>$Book["language"],"type"=>$Book["type"],"startTime"=>$time,"endTime"=>$time1,"translator"=>$Book["translator"]["id"]/*,"language"=>$Book["translator"]["language"],"Level"=>$Book["translator"]["level"]*/);
        }
        //$excelDataJson=json_decode($excelData,TRUE);
        //return $excelData;
           $spreadsheet->getActiveSheet()
            ->fromArray(
                $excelData,
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            );
        
        // Create Excel file and sve in your directory
        $writer = new Xlsx($spreadsheet);
        $writer->save(__DIR__ . '/mysheet.xlsx');
        return $arr;
    }
    public function toDate($data){
        return $data[0]."-".$data[1]."-".$data[2]."  ".$data[3].":".$data[4];
    }
}