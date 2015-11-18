<?php

class galeriaInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel' => 'Galeria de Fotos',
        'isdefault' => 'n',
        'detalhes'  => 'Gerencie as fotos enviadas para o site de maneira independente dos outros plugins.
            Com este plugin instalado você pode navegar por todas as fotos enviadas para o site.',
        'system'    => 'n',
    );
    
    protected $import = array(
        'usuario/gadget' => array(
            array('cod'=>'user_gallery', 'titulo'=>'Fotos', 'comp_method'=>'showUserPhotos', 'model'=>'galeria/foto/foto')
        )
    );


    public function install(){
        return true;
    }
    
    public function unstall(){
        return true;
    }
}