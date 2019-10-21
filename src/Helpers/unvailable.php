<?php

namespace src\Helpers;

class unvailable {
    public $id, $translator_id ,$description,$UnvailableTo,$Address,$unavailable_from,
    $unavailable_until,$created_at,$updated_at;
    
    public function __construct($id, $translator_id ,$description,$UnvailableTo,$Address,$unavailable_from,
    $unavailable_until,$created_at,$updated_at){
        $this->id = $id;
        $this->translator_id  = $translator_id ;
        $this->description  = $description ;
        $this->UnvailableTo  = $UnvailableTo ;
        $this->Address  = $Address ;
        $this->unavailable_from  = $unavailable_from ;
        $this->unavailable_until  = $unavailable_until;
        $this->created_at=$created_at;
        $this->updated_at=$updated_at;
    }
}

?>