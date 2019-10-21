<?php

namespace src\Helpers;

class Translator {
    public $id, $ADRESS ,$PADR,$feedBack,$language,$level,$languages,$workStart,$workEnd,$wante_lunch,$lunch_time,$lunch_duree,$working_hours,$lunch_time_fixed_switch,
    $lunch_time_range_switch,$lunch_time_from,$lunch_time_to;

    public function __construct($id, $ADRESS ,$PADR,$feedBack,$language,$level,$languages,
                                $workStart,$workEnd,$wante_lunch,$lunch_time,
                                $lunch_duree,$working_hours,$lunch_time_fixed_switch,
                                $lunch_time_range_switch,$lunch_time_from,$lunch_time_to){
        $this->id = $id;
        $this->ADRESS  = $ADRESS ;
        $this->PADR  = $PADR ;
        $this->feedBack  = $feedBack ;
        $this->language  = $language ;
        $this->level  = $level ;
        $this->languages  = $languages;$this->workStart=$workStart;$this->workEnd=$workEnd;
        $this->wante_lunch=$wante_lunch;$this->lunch_time=$lunch_time;$this->lunch_duree=$lunch_duree;
        $this->working_hours=$working_hours;
        $this->lunch_time_fixed_switch=$lunch_time_fixed_switch;
        $this->lunch_time_range_switch=$lunch_time_range_switch;
        $this->lunch_time_from=$lunch_time_from;
        $this->lunch_time_to=$lunch_time_to;
    }
}

?>