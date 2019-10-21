<?php

namespace src\Helpers;

class DtBooking {
    public $id, $customerId ,$endTime,$language,$startTime,$type;
   

    public function __construct($id, $customerId ,$endTime,$language,$startTime,$type){
        $this->id = $id;
        $this->customerId  = $customerId ;
        $this->endTime  = $endTime ;
        $this->language  = $language ;
        $this->startTime  = $startTime ;
        $this->type  = $type ;
    }
}

?>