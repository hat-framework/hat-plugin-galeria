<?php 
use classes\Classes\Actions;
class galeriaActions extends Actions{
    protected $permissions = array(
        'GerenciarFotos' => array(
            'nome'      => "galeria_gerenciar",
            'label'     => "Gerenciar Galeria",
            'descricao' => "Permite gerenciar as fotos",
            'default'   => 'n',
        ),
    );
    
    protected $actions = array(
        'galeria/foto/all' => array(
            'label' => 'Tipos de Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'galeria_gerenciar', 
            'breadscrumb' => array('usuario/login/todos','usuario/login/show','galeria/foto/all'),
        ),
    );
    
}