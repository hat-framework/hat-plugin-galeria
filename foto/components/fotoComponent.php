<?php

class fotoComponent extends classes\Component\Component{
    
    private $url_actions;
    private $enablegurl;
    private $flush   = "";
    private $nonimgs = array('pdf','doc','docx','xls');
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
                if(!is_array($foto) || empty($foto)){return "";}
                $url   = $this->getUrl($foto, "medium");
                $url2  = $this->getUrl($foto, "");
                $id    = $foto['cod_foto'];
                $class = ($id == $capa)?"is_capa":"";
                $album = (isset($foto['cod_album']) && $this->nonImage == false)?"data-lightbox='{$foto['cod_album']}'":"";
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
                    private $nonImage = false;
                    public function getUrl($foto, $sufix = "min"){
                        if(!in_array($sufix, array('min', 'max', 'medium', ''))) {$sufix = 'min';}
                        if(in_array($foto['ext'], $this->nonimgs)){
                            $this->nonImage = true;
                            $url = URL_IMAGENS . $foto['url'];
                            $this->getUrlNonImage($url, $sufix, $foto);
                        }
                        else{
                            $this->nonImage = false;
                            $sufix = ($sufix != "")? "_$sufix": $sufix;
                            $url = URL_IMAGENS . $foto['url'] . "$sufix." . $foto['ext'];
                        }
                        
                        getTrueUrl($url);
                        return $url;
                    }
                    
                            private function getUrlNonImage(&$url, $sufix, $foto){
                                if($sufix == ""){return;}
                                $url = "";
                                if(defined('CURRENT_TEMPLATE')){
                                    $url = classes\Classes\Registered::getTemplateLocationUrl(CURRENT_TEMPLATE);
                                }
                                if($url == ""){
                                    $url = classes\Classes\Registered::getTemplateLocationUrl('ppdh');
                                }
                                if($url !== "") {$url .= "/img/filetypes/{$foto['ext']}_$sufix.png";}
                            }
    
    public function enableGetUrl(){
        $this->enablegurl = true;
    }
    
}