<?php

namespace DigitalTolk\OptaplannerAdapter\Helpers;

class Booking {
    public $id, $customerId ,$endTime,$language,$startTime,$type,$date,$start,$endT;

    public function __construct($id, $customerId ,$endTime,$language,$startTime,$type,$date,$start,$endT){
        $this->id = $id;
        $this->customerId  = $customerId ;
        $this->endTime  = $endTime ;
        $this->language  = $language ;
        $this->startTime  = $startTime ;
        $this->type  = $type ;
        $this->date=$date;
        $this->start=$start;
        $this->endT=$endT;
    }
}

?>
