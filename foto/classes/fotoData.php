<?php

class galeria_fotoData extends \classes\Model\DataModel{
    
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
            'type' 	=> 'varchar',
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
    
}