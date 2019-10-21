<?php

namespace src\Helpers;

class excelArray {
    public $id, $customerId ,$endTime,$language,$startTime,$type,$idTranslator,$level,$translatorlanguage ;

    public function __construct($id, $customerId ,$endTime,$language,$startTime,$type,$idTranslator,$level,$translatorlanguage){
        $this->id = $id;
        $this->customerId  = $customerId ;
        $this->endTime  = $endTime ;
        $this->language  = $language ;
        $this->startTime  = $startTime ;
        $this->type  = $type ;
        $this->idTranslator  = $idTranslator ;
        $this->level  = $level ;
        $this->translatorlanguage  = $translatorlanguage ;
    }
}

?>