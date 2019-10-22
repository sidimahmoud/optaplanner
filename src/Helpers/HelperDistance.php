<?php

namespace DigitalTolk\OptaplannerAdapter\Helpers;

class HelperDistance {
    public $booking_id, $translator_id ,$distance,$eta;


    public function __construct($booking_id, $translator_id ,$distance,$eta){
        $this->booking_id = $booking_id;
        $this->translator_id  = $translator_id ;
        $this->distance  = $distance ;
        $this->eta  = $eta ;
    }
}

?>
