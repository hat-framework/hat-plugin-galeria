<?php

class galeria_albumModel extends \classes\Model\Model{
    protected $tabela = "galeria_album";
    protected $pkey   = "cod_album";

    public function inserir($post){

    	$this->LoadModel("usuario/login", 'uobj');
    	$post['cod_autor'] = $this->uobj->getCodUsuario();
        if($post['cod_autor'] == ""){return $this->setErrorMessage("É preciso estar logado para criar um album");}
        if(array_key_exists("used", $post)) {$post['used'] = $post['used'];}
    	if(!$this->db->Insert($this->tabela, $post)){
            return $this->setErrorMessage("Album de fotos: " . $this->db->getErrorMessage());
        }
        
        if(isset($_SESSION['empty_album'])) {unset($_SESSION['empty_album']);}
        return $this->setSuccessMessage("Dados Inseridos Corretamente!");
    }
    
    public function editar($id, $post, $camp = "") {
        $this->LoadModel("usuario/login", 'uobj');
    	$cod_autor = $this->uobj->getCodUsuario();
        if(!$this->albumAutentication($cod_autor)) return false;
        $post['cod_autor'] = $cod_autor;
        if($this->fkedit) $post['used'] = 'used';
        return parent::editar($id, $post, $camp);
    }
    
    public function apagar($cod_album, $chave = ""){
        $item = $this->selecionar(array(), "`cod_album` = '$cod_album'");
        $item = array_shift($item);
        if($item == "" || empty ($item)) {
            return false;
        }
        
        $this->LoadModel("galeria/foto", "gfoto");
        if(!$this->gfoto->apagarAlbum($cod_album)){
            $this->setErrorMessage($this->gfoto->getErrorMessage());
            return false;
        }
        
        return parent::apagar($cod_album);
    }
    
    public function setAlbumFull($cod_album){
        
        if($cod_album == ""){
            $this->setErrorMessage("O código do album não pode ser vazio");
            return false;
        }
        
        $where = "`cod_album` = '$cod_album'";
        $var = $this->selecionar(array(), $where, 1);
        $var = array_shift($var);
        if(empty($var)){
            //$this->setErrorMessage($this->db->getSentenca());
            $this->setErrorMessage("O album selecionado não existe!");
            return false;
        }
        
        $post = array('used' => 'used');
        if($var['used'] == 'unused'){
            //echo "<h4>editando album</h4>";
            if(!$this->editar($cod_album, $post)) 
                    return false;
            //echo "<h4>fim da edição</h4>";
        }
        return true;
    }
    
    public function getEmptyAlbum($usuario){
        $where = "`used` = 'unused' AND `cod_autor` = '$usuario'";
        $var = $this->selecionar(array(), $where, 1);
        if(!is_array($var) || empty($var)){
            $this->inserir(array('used' => 'unused'));
            $var = $this->selecionar(array(), $where, 1);
        }
        return array_shift($var);
    }
    
    public function inserirFoto($cod_album, $post){
        
        //faz a autenticação do album
        $album = $this->getItem($cod_album);
        if(empty ($album)){
            $this->setErrorMessage("O album procurado não existe");
            return false;
        }
        //autenticacao do album
        if(!$this->albumAutentication($album['cod_autor'])) return false;
        
        //insere a foto
        $post['cod_album'] = $cod_album;
    	$this->LoadModel("galeria/foto","fotos");
    	if(!$this->fotos->inserir($post)){
            $this->setErrorMessage($this->fotos->getErrorMessage());
            return false;
    	}
    	return true;
    	
    }
    
    public function JoinAlbum($tabela, $pkey){
        $this->db->Join($tabela, $this->tabela, array($pkey), array($this->pkey), "LEFT");
        
        $this->LoadModel("galeria/foto", 'gfoto');
        $tabela = $this->gfoto->getTable();
        $pkey   = $this->gfoto->getPkey();
        $this->db->Join($this->tabela, $tabela,  array($this->pkey), array($pkey), "LEFT");
    }

    public function getFotos($cod_album){
        $this->LoadModel("galeria/foto", "gfotos");
        return $this->gfotos->getAlbum($cod_album);
    }
       
    public function getCapa($cod_album){
        $var = $this->getFotos($cod_album);
        if(empty ($var)) {return "";}
        $var = array_shift($var);
        return $var['cod_foto'];
    }
    
    public function albumAutentication($cod_autor){
        
        //se cod_autor veio de um get item, ele será um array
        if(is_array($cod_autor)){
            $cod_autor = array_keys($cod_autor);
            $cod_autor = array_shift($cod_autor);
        }
        
        $this->LoadModel('usuario/login', 'uobj');
        $userid = $this->uobj->getCodUsuario();
        if($userid != $cod_autor){
            if(!$this->uobj->UserIsAdmin()){
                $this->setErrorMessage("Você não tem permissão de alterar este album!");
                return false;
            }
        }
        return true;
    }
    
    public function getPublicAlbum(){
        $album = $this->selecionar(array(), "where cod_album = '1'");
        if(empty($album)){
            $this->LoadModel('usuario/login', 'uobj');
            $cod = $this->uobj->getCodUsuario();
            $post = array('cod_album' => '1', 'cod_autor' => $cod);
            $this->inserir($post);
        }
        return 1;
    }
}
