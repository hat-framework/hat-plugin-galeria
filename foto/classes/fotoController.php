<?php

class fotoController extends \classes\Controller\Controller{
    
    public function __construct($vars) {
        parent::__construct($vars);
        $this->LoadModel("galeria/foto", "model");
    }
    
    public function index(){
    	$vars['classe'] = __CLASS__;
    	$vars['metodo'] = __METHOD__;
    	$this->display("index", $vars);
    }
    
    public function all(){
        $cod_usuario = array_shift($this->vars);
        if(!usuario_loginModel::IsWebmaster()){
            if($cod_usuario != usuario_loginModel::CodUsuario()){
                $this->registerVar('erro', "Você não tem permissão para acessar este link!");
                return $this->display("");
            }
        }
        
        $fotos = $this->LoadModel('galeria/foto', 'gft')->getUserPhotos($cod_usuario);
        $this->LoadResource('files/zip', 'zip');
        $files = array();
        foreach($fotos as $foto){
            $u          = DIR_IMAGENS . $foto['url'] .".". $foto['ext'];
            $files[] = str_replace(".{$foto['ext']}.{$foto['ext']}", ".{$foto['ext']}", $u);
        }
        $zipname = DIR_FILES. "/downloads/fotos_user_$cod_usuario";
        $this->LoadResource('files/zip', 'zip')->donwloadZipFiles($zipname, $files, true, true);
        print_rh($this->zip->getMessages());
    }
    
}
