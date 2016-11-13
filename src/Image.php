<?php

namespace CapsLockStudio\ExPage;

use phpQuery;
use ZipArchive;

class Image
{

    public function __construct($page, $cookie, $save, $header = [], $pack = "", $deleteAfterPack = false)
    {
        phpQuery::newDocument($page);
        $total = pq(".ptb td")->text();
        $total = explode("\n", $total);
        $max   = 0;
        foreach ($total as $value) {
            $value = trim($value);
            if ($max < intval($value)){
                $max = intval($value);
            }
        }

        $origin  = pq(".ptds>a")->eq(0)->attr("href");
        $slice   = explode("/g/", $origin);
        $slice   = explode("/", $slice[1]);
        $dataId  = $slice[0];
        $imageId = 0;
        for ($i = 0; $i < $max; $i++) {
            if ($i != 0) {
                $next     = "{$origin}?p={$i}";
                $nextData = Curl::http("get", $next, [], false, $cookie, $header);
                phpQuery::newDocument($nextData);
            }

            $queries = pq(".gdtm>div>a");
            foreach ($queries as $query) {
                $href = pq($query)->attr("href");
                $data = Curl::http("get", $href, [], false, $cookie, $header);
                phpQuery::newDocument($data);
                $url = pq("#i3>a>img")->eq(0)->attr("src");

                if (preg_match("/509\.gif/", $url)) {
                    $queries->append(pq($query));
                    continue;
                }

                do {
                    $content = Curl::http("get", $url, [], false, $cookie, $header);
                    $ext     = $this->getExtension($content);
                } while ($ext === "bak");

                $endFilename = "{$dataId}-" . sprintf("%03d", $imageId) . ".{$ext}";
                $filename    = "{$save}/{$endFilename}";
                $imageId ++;
                file_put_contents($filename, $content);

                if ($pack) {
                    $zip = new ZipArchive();
                    while (true) {
                        if ($zip->open("{$pack}/{$dataId}.zip", ZipArchive::CREATE)) {
                            $zip->addFile($filename, $endFilename);
                            $zip->close();

                            if ($deleteAfterPack) {
                                @unlink(realpath($filename));
                            }

                            break;
                        }

                        usleep(rand(0, 1000));
                    }
                }
            }
        }
    }

    private function getExtension($content)
    {
        $extensions = array(
            IMAGETYPE_GIF     => "gif",
            IMAGETYPE_JPEG    => "jpg",
            IMAGETYPE_PNG     => "png",
            IMAGETYPE_SWF     => "swf",
            IMAGETYPE_PSD     => "psd",
            IMAGETYPE_BMP     => "bmp",
            IMAGETYPE_TIFF_II => "tiff",
            IMAGETYPE_TIFF_MM => "tiff",
            IMAGETYPE_JPC     => "jpc",
            IMAGETYPE_JP2     => "jp2",
            IMAGETYPE_JPX     => "jpx",
            IMAGETYPE_JB2     => "jb2",
            IMAGETYPE_SWC     => "swc",
            IMAGETYPE_IFF     => "iff",
            IMAGETYPE_WBMP    => "wbmp",
            IMAGETYPE_XBM     => "xbm",
            IMAGETYPE_ICO     => "ico"
        );

        $setIf = @exif_imagetype("data://text/plain," . $content);

        return isset($extensions[$setIf]) ? $extensions[$setIf] : "bak";
    }
}
?>
