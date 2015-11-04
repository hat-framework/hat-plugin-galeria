<?php

class albumComponent extends classes\Component\Component{
    
    private $url_actions;
    private $url_basic = "";
    
    public function __construct() {
        $this->url_actions = URL_RESOURCES . "upload/lib/actions";
    }
    
    public function getTemplateLinkCapa(){
        $arr = array(URL, 'url', 'min', 'ext');
    }
    
    public function getLinkCapa($cod_album, $size = "min", $sem_foto = true){
        $this->LoadModel("galeria/album", 'galbum');
        $capa = $this->galbum->getCapa($cod_album);
        $url_img = "";
        if($capa != ""){
            $size = "_$size";
            $this->LoadModel("galeria/foto", 'gfoto');
            $foto = $this->gfoto->getItem($capa);
            $url_img = URL_IMAGENS . $foto['url'].$size.".".$foto['ext'];
        }
        elseif($sem_foto) $url_img = URL . "templates/".TEMPLATE_DEFAULT."/img/sem_foto.jpg";
        return $url_img;
    }
    
    public function getCapa($album){
        if(!is_array($album)){
            $this->LoadModel("galeria/album", 'galbum');
            $album = $this->galbum->getItem($album);
        }
        return array_key_exists('cod_album', $album) ? $album['cod_album'] : "";
    }
    
    public function show($model, $item) {
        $this->drawAlbum($item, "");
        //parent::show($model, $item);
    }
    
    public function drawAlbum($album, $title, $class = '', $show_empty_album = false, $img_size = "min", $id = ""){

        //recupera o album caso ele não tenha sido passado por parâmetro
        $this->LoadModel("galeria/album", 'galbum');
        $album    = (is_array($album))?$album: $this->galbum->getItem($album);

        //recupera o tamanho de cada imagem a ser exibida
        $img_size = in_array($img_size, array("min", "medium", "max"))?$img_size:"min";
        
        //carrega a aparencia
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('galeria');

        //carrega os dados do album
        $cod_album = $album['cod_album'];
        $images = $this->galbum->getFotos($cod_album);

        //trata o caso do album vazio
        $url_imagens = URL_IMAGENS;
        if(empty ($images)){
            if(!$show_empty_album) return false;
            $url          = $this->Html->getUrlImage('semfoto/sem_foto.jpg');
            $url_imagens  = str_replace('sem_foto.jpg', "", $url);
            $images[] = array(
                'ext' => "jpg",
                'url' => "sem_foto"
            );
        }

        //imprime o album
        $title   = ($title != "")? "<h4>$title</h4>": "";
        $id = ($id == "")?"":"id='$id'";
        $print = "
        <div class='galeria $class' $id>
            $title
            <div class='fotos-panel'>
                    <div id='sliderminiaturas'>";
                        foreach($images as $img){
                            
                            $img_max = ($this->url_basic == "")?$url_imagens . $img['url'].".".$img['ext']: $this->url_basic;
                            $img_min = $url_imagens . $img['url']."_$img_size.".$img['ext'];
                            //$img_med = $url_imagens . $img['url']."_max.".$img['ext'];
                            $print .= "
                            <div class='fotominiatura'>
                                <a href='$img_max' class='img foto'>
                                    <img class='zoom-tiny-image' src='$img_min' alt='Thumbnail'/>
                                </a>
                            </div>
                            ";
                        }
                
                   $print .= "<div class='clear'></div>
                    </div>
            </div>
        
        </div>
        ";
        echo $print;
        return true;
    }

    public function drawCapaAlbum($album, $title, $class = '', $img_size = "min"){
        
        //carrega as classes necessárias
        $this->LoadResource('html', 'Html');
        $this->Html->LoadCss('galeria');
        $this->LoadModel("galeria/album", 'galbum');
        
        //recupera o tamanho de cada imagem a ser exibida
        $album       = (is_array($album))?$album['cod_album']: $album;
        $img_size    = in_array($img_size, array("min", "medium", "max"))?$img_size:"min";
        $capa        = $this->getLinkCapa($album, $img_size);
        $title       = ($title != "")? "<h4>$title</h4>": "";
        $url_imagens = URL_IMAGENS;
        $img_max     = $this->url_basic;

        $print = "
        <div class='galeria $class'>
            $title
            <div class='fotos-panel'>
                    <div id='sliderminiaturas'>
                            <div class='fotominiatura'>
                                <a href='$img_max' class='img foto'>
                                    <img src='$capa' alt='Thumbnail'/>
                                </a>
                            </div>
                            <div class='clear'></div>
                    </div>
            </div>

        </div>
        ";
        echo $print;
    }

    public function setUrlBasic($url){
        $this->LoadResource("html", "Html");
        $this->url_basic = $this->Html->getLink($url);
    }
    
}