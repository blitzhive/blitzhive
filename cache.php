<?php
class SimpleCachePhp {

    private $folderCache;
    private $filename;

    function __construct($filename, $time = 3, $tamano=0, $ignoreKeysOnParametrize = array(), $folderCache = "cache", $subName="") {	
        if (!is_array($ignoreKeysOnParametrize))
            die("SimpleCachePhp: Ignored keys it's not an Array!");
		$realFilename=$filename;
        $filename = self::getFileName($filename, $subName, $ignoreKeysOnParametrize);
		
		

        if (!$folderCache)
            $folderCache = self::obterDocumentRoot() . "cache";			

        $fileFullName = $folderCache . "/" . $filename;
        if (self::verificaCacheFile($fileFullName, $time, $tamano ,$realFilename)) {
            include_once $fileFullName;
			echo utf8_encode("<i style='color:#848484;margin-left:10px;float:left;'>Cargando de caché.</i>");
            exit();
        } else {
            $this->folderCache = $folderCache;
            $this->filename = $filename;
            ob_start();
        }
    }

    public function CacheEnd() {
        $s = ob_get_contents();				
        self::makeCacheFiles($this->folderCache, $this->filename, $s);
        ob_end_flush();
    }

    private function getFileName($filename,$subName, $ignoreKeysOnParametrize) {

        $s = basename($filename);
        //$s .= "-cached";
		//$s .=$subName;
        //$s .= "_-s-_" . self::parameterize_array($_SESSION, $ignoreKeysOnParametrize);
		$s .= "_-s-_" .$subName;
        $s .= "_-q-_" . self::parameterize_array($_GET, $ignoreKeysOnParametrize);
        return self::toRewriteString($s);
		//return $s;
    }

    private static function parameterize_array($array, $ignoreKeysOnParametrize) {
        $out = array();
        foreach ($array as $key => $value) {
            if (!in_array($key, $ignoreKeysOnParametrize))
                $out[] = "$key-$value";
        }

        return join("_", $out);
    }

    private static function toRewriteString($s) {
        $s = trim($s);
        $s = mb_strtolower($s, 'UTF-8');


        $s = str_replace("á", "a", $s);
        $s = str_replace("à", "a", $s);
        $s = str_replace("ã", "a", $s);
        $s = str_replace("â", "a", $s);
        $s = str_replace("ä", "a", $s);

        //letra e
        $s = str_replace("é", "e", $s);
        $s = str_replace("ê", "e", $s);
        $s = str_replace("è", "e", $s);
        $s = str_replace("ë", "e", $s);

        //letra i
        $s = str_replace("í", "i", $s);
        $s = str_replace("ì", "i", $s);
        $s = str_replace("î", "i", $s);
        $s = str_replace("ï", "i", $s);

        //letra o
        $s = str_replace("ó", "o", $s);
        $s = str_replace("ô", "o", $s);
        $s = str_replace("õ", "o", $s);
        $s = str_replace("ò", "o", $s);
        $s = str_replace("ö", "o", $s);

        //letra u
        $s = str_replace("ú", "u", $s);
        $s = str_replace("ü", "u", $s);
        $s = str_replace("û", "u", $s);
        $s = str_replace("ù", "u", $s);

        //letra c
        $s = str_replace("ç", "c", $s);
		
		$s = str_replace("ñ", "n", $s);

        //ultimos caracteres indesejaveis
        $s = str_replace("  ", " ", $s);
        $s = str_replace(" ", "-", $s);

        $s = preg_replace("/[^a-zA-Z0-9_.-]/", "", $s);
        $s = str_replace("-.", ".", $s);
        return $s;
    }

    /**
     * Cache verification in secs.'
     * @param string $filename FIle name
     * @param int $time time cached
     * @return boolean 
     */
    public static function verificaCacheFile($filename, $time, $tamano,$realFileName) {
	

 if(file_exists($filename)){
 $dateFile=(int)filectime($filename);
if(filemtime($filename)!==FALSE)$dateFile=(int)filemtime($filename);
}
if(file_exists($filename) && $tamano==0 && (time() - $time) < $dateFile)
		{
		
		    return true;
        } else {
		//echo "No cargo";
            return false;
        }
    }

    /**		
		Directory/path verification
     */
    private static function verificaDiretorios($folder) {

        if (!is_dir($folder)) {
            if (!mkdir($folder, 0777, true)) {
                die(utf8_encode("<i>No podemos crear $folder para usar la caché</i>"));
                return false;
            }
        }


        if (!is_writable($folder)) {
            if (!chmod($folder, 0777)) {
                die(utf8_encode("<i>No podemos asignar permisos a $folder para usar la caché</i>"));
                return false;
            }
        }

        return true;
    }

    public static function makeCacheFiles($folderCache, $fileName, $content) {
	
        self::verificaDiretorios($folderCache);

        $filename = $folderCache . "/" . $fileName;

        $fp = @fopen($filename, "w");
        if ($fp) {
            fwrite($fp, $content);
            fclose($fp);

            if (file_exists($filename)) {
                chmod($filename, 0777);
                return true;
            }
        }

        echo utf8_encode("<i>no se puede crear la caché</i>");
        return false;
    }

    public static function obterDocumentRoot() {
        $AppRoot = $_SERVER['DOCUMENT_ROOT'];

        if ($AppRoot[strlen($AppRoot) - 1] != "/") {
            $AppRoot.= "/";
        }

        return $AppRoot;
    }

}
?>