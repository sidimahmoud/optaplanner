<?php

namespace DigitalTolk\OptaplannerAdapter\Helpers;

class customTranslators {
    public $booking_id, $translator_id ,$temp_travel_time_public,$temp_travel_time_car,$temp_travel_distance_public,$temp_travel_distance_car;

    public function __construct($booking_id, $translator_id ,$temp_travel_time_public,$temp_travel_time_car,$temp_travel_distance_public,$temp_travel_distance_car){
        $this->booking_id = $booking_id;
        $this->translator_id  = $translator_id ;
        $this->temp_travel_time_public  = $temp_travel_time_public ;
        $this->temp_travel_time_car  = $temp_travel_time_car ;
        $this->temp_travel_distance_public  = $temp_travel_distance_public ;
        $this->temp_travel_distance_car  = $temp_travel_distance_car ;
    }
}

?>
