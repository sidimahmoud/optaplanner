<?php

namespace DigitalTolk\OptaplannerAdapter\Helpers;

class TfTranslator {
    public $id, $ADRESS ,$PADR,$feedBack,$language,$level,$languages;

    public function __construct($id, $ADRESS ,$PADR,$feedBack,$language,$level){
        $this->id = $id;
        $this->ADRESS  = $ADRESS ;
        $this->PADR  = $PADR ;
        $this->feedBack  = $feedBack ;
        $this->language  = $language ;
        $this->level  = $level ;
    }
}

?>
