<?php

class fotoComponent extends classes\Component\Component{
    
    private $url_actions;
    private $enablegurl;
    private $flush = "";
    public function __construct() {
        $this->url_actions = URL_RESOURCES . "upload/lib/actions";
        $this->url_images  = URL_RESOURCES . "upload/lib/images";
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
        $url = $this->getUrl($foto, "min");
        $id  = $foto['cod_foto'];
        if($id == $capa) $class = "is_capa";
        else             $class = "";
        $var = "
        <div class='img_container $class'>
            <a href='$this->url_actions/capa.php?id=$id' id='foto_$id' class='img album_capa'>
                <img src='$url' class='img_uploaded'/>
            </a>
            <a href='$this->url_actions/excluir.php?id=$id' id='foto_$id' class='img album_excluir' alt='excluir'>
                <img src='$this->url_images/excluir.gif'/>
            </a>";
            
            //pog das feias.. mas fazer o que ne?
            if($this->enablegurl){
                $url = "javascript:UploadifyDialog.insert(\"$this->url_actions/geturl.php?id=$id\",\"".URL_IMAGENS."\")";
                $var .= "<a href='$url' id='foto_$id' class='img album_geturl'>
                    <img src='$this->url_images/ok.png'/>
                </a>";
            }
        $var .= "</div>";
        if($print) echo $var;
        else $this->flush .= $var;
    }
    
    public function enableGetUrl(){
        $this->enablegurl = true;
    }
    
    public function getUrl($foto, $sufix = "min"){
        if(!in_array($sufix, array('min', 'max', 'medium', ''))) $sufix = 'min';
        $sufix = ($sufix != "")? "_$sufix": $sufix;
        return URL_IMAGENS . $foto['url'] . "$sufix." . $foto['ext'];
    }
}

?>
