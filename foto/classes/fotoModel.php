<?php

class galeria_fotoModel extends \classes\Model\Model{
    
    protected $has_features = true;
    protected $tabela = "galeria_foto";
    protected $pkey   = "cod_foto";
   
    public function getAlbum($cod_album){
        $dados = array_keys($this->dados);
        unset($dados['cod_album']);
        $where = "`cod_album` = '$cod_album'";
        $var = $this->selecionar($dados, $where);
        //echo $this->db->getSentenca();
        return $var;
    }
    
    public function apagarAlbum($cod_album){
        $where = "`cod_album` = '$cod_album'";
        if(!$this->db->Delete($this->tabela, $where)){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        $this->setSuccessMessage("Album apagado com sucesso!");
        return true;
    }
    
    public function apagar($valor, $chave = "") {

        $item = $this->getItem($valor, $chave);
        if(empty ($item)){return true;}
        
        $cod_album = array_shift($item['cod_album']);
        $this->LoadModel("galeria/album", 'galbum');
        $album = $this->galbum->getItem($cod_album);
        
        $autor = array_keys($album['cod_autor']);
        $autor = end($autor);
        if(!$this->galbum->albumAutentication($autor)){
            $this->setErrorMessage("Você não tem permissão de apagar esta foto");
            return false;
        }
        
        return parent::apagar($valor, $chave);
    }
    
    public function inserir($dados) {
        if(isset($dados['url'])){
            getTrueDir($dados['url']);
            $dados['url'] = str_replace("\\", '\\\\', $dados['url']);
        }
        return parent::inserir($dados);
    }
    
    public function getUserPhotos($cod_usuario = ""){
        if($cod_usuario == ""){$cod_usuario = usuario_loginModel::CodUsuario();}
        $user = $this->antinjection($cod_usuario);
        if($user == ""){$user = usuario_loginModel::CodUsuario();}
        
        $this->join('galeria/album', array('cod_album'), array('cod_album'));
        return $this->selecionar(array("$this->tabela.*"), "cod_autor='$user'");
    }
    
}