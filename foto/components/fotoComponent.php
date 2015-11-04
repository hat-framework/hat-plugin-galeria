<?php

class fotoComponent extends classes\Component\Component{
    
    private $url_actions;
    private $enablegurl;
    private $flush = "";
    public function __construct() {
        $res               = classes\Classes\Registered::getResourceLocationUrl('upload');
        $this->url_actions = $res . "/src/lib/actions";
        $this->url_images  = $res . "/src/lib/images";
    }
    
    public function DrawAlbum($fotos, $cod_album, $print = true){
        
        if(!is_array($fotos) || empty ($fotos)) return;
        $this->LoadModel('galeria/album', 'galbum');
        $capa = $this->galbum->getCapa($cod_album);

        foreach($fotos as $foto){
            $this->DrawPicture($foto, $print, $capa);
        }
        if(!$print){
            $flush = $this->flush;
            $this->flush = "";
            return $flush;
        }
    }

    
            public function DrawPicture($foto, $print, $capa = ""){
                $url   = $this->getUrl($foto, "medium");
                $url2  = $this->getUrl($foto, "");
                $id    = $foto['cod_foto'];
                $class = ($id == $capa)?"is_capa":"";
                $album = isset($foto['cod_album'])?"data-lightbox='{$foto['cod_album']}'":"";
                $var   = "
                <div class='img_container $class col-xs-6 col-sm-4 col-md-3 col-lg-2'>
                    <a href='$url2' target='_BLANK' id='foto_$id' class='img album_capa col-xs-12' $album>
                        <img src='$url' class='img_uploaded'/>
                    </a>
                    <div>
                        <a href='$this->url_actions/excluir.php?id=$id' id='foto_$id' class='img album_excluir' alt='excluir'>
                            <i class='fa fa-2x fa-times'></i>
                        </a>
                        <a href='$url2' id='foto_$id' class='img' alt='download' target='_BLANK'>
                            <i class='fa fa-2x fa-cloud-download'></i>
                        </a>
                    </div>
                </div>";
                if($print) {echo $var;}
                else {$this->flush .= $var; return $var;}
            }
            
                    public function getUrl($foto, $sufix = "min"){
                        if(!in_array($sufix, array('min', 'max', 'medium', ''))) {$sufix = 'min';}
                        $sufix = ($sufix != "")? "_$sufix": $sufix;
                        $url = URL_IMAGENS . $foto['url'] . "$sufix." . $foto['ext'];
                        getTrueUrl($url);
                        return $url;
                    }
    
    public function enableGetUrl(){
        $this->enablegurl = true;
    }
    
    
}