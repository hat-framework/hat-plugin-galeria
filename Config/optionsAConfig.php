<?php

class plugins_galeria_Config_optionsAConfig extends configModel {
    
    public $name = "Opções do plugin";
    public function  __construct() {
        $this->setFilename(__FILE__, __CLASS__);
    }

}

?>