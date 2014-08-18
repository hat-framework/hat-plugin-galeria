<?php

class indexController extends \classes\Controller\Controller{
    
    public function __construct($vars) {
        parent::__construct($vars);
        $this->LoadModel("galeria/album", "model");
    }
    
    public function index(){
    	$vars['classe'] = __CLASS__;
    	$vars['metodo'] = __METHOD__;
    	$this->display("index", $vars);
    }
    
}

?>
