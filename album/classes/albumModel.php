<?php

class galeria_albumModel extends \classes\Model\Model{
    protected $tabela = "galeria_album";
    protected $pkey   = "cod_album";
    protected $dados = array(
        'cod_album' => array(
            'name'    => "Álbum",
            'pkey'    => true,
            'ai'      => true,
            'type'    => 'int',
            'size'    => '11',
            'grid'    => true,
            'especial'=> 'hide',
            'notnull' => true
         ),
        
        'cod_autor' => array(
            'name'      => 'Autor',
            'type' 	=> 'int',
            'especial'  => 'hide',
            'grid'      => true,
            'fkey'      => array(
                'model' 	=> 'usuario/login', 
                'cardinalidade' => '1n',//nn 1n 11
                'keys'          => array('cod_usuario', 'email')
            )
         ),
        
        'foto_capa' => array(
            'name'      => 'Capa',
            'type' 	=> 'int',
            'especial'  => 'hide',
            'fkey'      => array(
                'model' 	=> 'galeria/foto', 
                'cardinalidade' => '11',//nn 1n 11,
                'keys'          => array('cod_foto', 'cod_foto')
            )
         ),
        
        'album_titulo' => array(
            'name'    => 'Título do Álbum',
            'type'    => 'varchar',
            'size'    => '32',
            'especial' => 'hide',
       	 ),
       	
       	'album_descricao' => array(
            'name'  => 'Descrição do Álbum',
            'type'  => 'varchar',
            'size'  => '256',
            'especial' => 'hide',
       	 ), 
        
        'album_local' => array(
            'name'  => 'Local do Álbum',
            'type'  => 'varchar',
            'size'  => '256',
            'especial' => 'hide',
       	 ), 
        
        'ordem' => array(
            'name'  => 'Ordem',
            'type'  => 'int',
            'size'  => '11',
            'grid'  => true,
            'feature' => array(
                'feature_name'  => 'GALERIA_ORDEM_DA_FOTO',
                'feature_value' => 'true'
            )
         ),
        
        'used' => array(
            'name'      => "Utilizado",
            'type'      => 'enum',
            'especial'  => 'hide',
            'notnull'   => true,
            'default'   => 'used',
            'private'   => true,
            'options'   => array(
                'used'   => 'usado',
                'unused' => 'não usado'
            )
         ),
        
        '__album__' => array(
            'name'      => 'Fotos',
            'especial'  => 'album'
        )
        
        
    );
    
    public function inserir($post){

    	$this->LoadModel("usuario/login", 'uobj');
        $var = array();
    	$var['cod_autor'] = $this->uobj->getCodUsuario();
        if($var['cod_autor'] == ""){
            $this->setErrorMessage("É preciso estar logado para criar um album");
            return false;
        }
        if(array_key_exists("used", $post)) $var['used'] = $post['used'];
        
    	if(!$this->db->Insert($this->tabela, $var)){
            $this->setErrorMessage("Album de fotos: " . $this->db->getErrorMessage());
            return false;
        }
        
        if(isset($_SESSION['empty_album'])) unset($_SESSION['empty_album']);
        $this->setSuccessMessage("Dados Inseridos Corretamente!");
        return true;
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
    
    public function setCapa($cod_album, $cod_foto){
        
        $album = $this->getItem($cod_album);
        if(empty ($album)){
            $this->setErrorMessage("O album indicado não exite!");
            return false;
        }
        if(!$this->albumAutentication($album['cod_autor'])) return false;
        
        $this->LoadModel("galeria/foto", 'gfoto');
        $foto = $this->gfoto->getItem($cod_foto);
        if(empty ($foto)){
            $this->setErrorMessage("A foto indicada não existe!");
            return false;
        }
        
        $foto_cod_album = array_shift($foto['cod_album']);
        if($foto_cod_album != $cod_album){
            $this->setErrorMessage("A foto selecionada para capa não pertence ao album!");
            return false;
        }
        
        $post['cod_album'] = $album['cod_album'];
        $post['foto_capa'] = $cod_foto;
        if(!$this->editar($cod_album, $post)){
            return false;
        }
        
        $this->setSuccessMessage("Album alterado com sucesso!");
        return true;
        
    }
    
    public function getCapa($cod_album){
        $var = $this->selecionar(array('foto_capa'), "`cod_album` = '$cod_album'");
        $var = array_shift($var);
        if($var['foto_capa'] == ""){
            $var = $this->getFotos($cod_album);
            if(empty ($var)) return "";
            $var = array_shift($var);
            $var['foto_capa'] = $var['cod_foto'];
        }
        return $var['foto_capa'];
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

?>
