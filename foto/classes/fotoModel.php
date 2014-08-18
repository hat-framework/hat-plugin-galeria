<?php

class galeria_fotoModel extends \classes\Model\Model{
    
    protected $has_features = true;
    protected $tabela = "galeria_foto";
    protected $pkey   = "cod_foto";
    protected $dados = array(
        
        'cod_foto' => array(
            'name'    => "Foto",
            'pkey'    => true,
            'ai'      => true,
            'type'    => 'int',
            'size'    => '11',
            'notnull' => true
         ),
         
         'cod_album' => array(
            'name'      => 'Album',
            'type' 	=> 'int',
            'notnull'   => true, 
            'especial'  => 'album',
            'fkey'      => array(
                'model' 	=> 'galeria/album', 
                'cardinalidade' => '1n',//nn 1n 11
                'keys'          => array('cod_album', 'cod_album')
            )
         ),
        
        'ext' => array(
            'name'    => 'Extensão',
            'type'    => 'varchar',
            'size'    => '5', 
            'notnull' => true
       	 ),
       	
       	'url' => array(
            'name'    => 'Link da imagem',
            'type'    => 'varchar',
            'size'    => '200', 
            'notnull' => true
       	 ),
       	
       	'titulo' => array(
            'name'    => 'Título da imagem',
            'type'    => 'varchar',
            'size'    => '32',
            'feature' => array(
                'feature_name'  => 'GALERIA_DETALHES_DA_FOTO',
                'feature_value' => 'true'
            )
       	 ),
       	
       	'descricao' => array(
            'name'  => 'Descrição da imagem',
            'type'  => 'varchar',
            'size'  => '256',
            'feature' => array(
                'feature_name'  => 'GALERIA_DETALHES_DA_FOTO',
                'feature_value' => 'true'
            )
       	 ), 
        
        'local' => array(
            'name'  => 'Local da imagem',
            'type'  => 'varchar',
            'size'  => '256',
            'feature' => array(
                'feature_name'  => 'GALERIA_LOCAL_DA_FOTO',
                'feature_value' => 'true'
            )
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
         )
     );
    
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
    
}

?>
