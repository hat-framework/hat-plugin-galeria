<?php

class galeria_albumData extends \classes\Model\DataModel{
    
    protected $dados  = array(
        'cod_album' => array(
            'name'    => "Álbum",
            'pkey'    => true,
            'type'    => 'varchar',
            'size'    => '64',
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
    
}