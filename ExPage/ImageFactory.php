<?php

namespace ExPage;

class ImageFactory
{
    
    public function __construct($page, $cookie, $save, $header = array(), $pack = "")
    {
        \phpQuery::newDocument($page);
        $total = pq(".ptb td")->text();
        $total = explode("\n", $total);
        $max = 0;
        foreach ($total as $value) {
            $value = trim($value);
            if ($max < intval($value)){
                $max = intval($value);
            }
        }

        $ori = pq(".ptds>a")->eq(0)->attr("href");
        $slice = explode("/g/", $ori);
        $slice = explode("/", $slice[1]);
        $dataId = $slice[0];
        $o = 0;
        for ($i = 0; $i < $max; $i++) { 
            if ($i != 0) {
                $next = $ori . "?p=" . $i;
                $nextData = Curl::http("get", $next, array(), false, $cookie, $header);
                \phpQuery::newDocument($nextData);
            }


            $queries = pq(".gdtm>div>a");
            foreach ($queries as $value) {
                $href = pq($value)->attr("href");
                $data = Curl::http("get", $href, array(), false, $cookie, $header);
                \phpQuery::newDocument($data);
                $url = pq("#i3>a>img")->eq(0)->attr("src");
                
                if (preg_match("/509\.gif/", $url)) {
                    $queries->append(pq($value));
                    continue;
                }

                $content = Curl::http("get", $url, array(), false, $cookie, $header);
                $ext = $this->getExtension($content);
                $filename = "{$save}/{$dataId}-" . sprintf("%03d", $o) . ".{$ext}";
                $o ++;
                file_put_contents($filename, $content);
                
                if (!empty($pack)) {
                    $zip = new \ZipArchive();
                    $zip->open("{$pack}/{$dataId}.zip", ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
                    $zip->addFile($filename, "{$dataId}.{$ext}");
                    $zip->close();
                }
            }
        }
    }

    private function getExtension($content)
    {
        $extensions = array(
            IMAGETYPE_GIF => "gif",
            IMAGETYPE_JPEG => "jpg",
            IMAGETYPE_PNG => "png",
            IMAGETYPE_SWF => "swf",
            IMAGETYPE_PSD => "psd",
            IMAGETYPE_BMP => "bmp",
            IMAGETYPE_TIFF_II => "tiff",
            IMAGETYPE_TIFF_MM => "tiff",
            IMAGETYPE_JPC => "jpc",
            IMAGETYPE_JP2 => "jp2",
            IMAGETYPE_JPX => "jpx",
            IMAGETYPE_JB2 => "jb2",
            IMAGETYPE_SWC => "swc",
            IMAGETYPE_IFF => "iff",
            IMAGETYPE_WBMP => "wbmp",
            IMAGETYPE_XBM => "xbm",
            IMAGETYPE_ICO => "ico"
        );

        $setIf = exif_imagetype("data://text/plain," . $content);

        return isset($extensions[$setIf]) ? $extensions[$setIf] : "bak";
    }
}
?>