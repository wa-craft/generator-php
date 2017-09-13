<?php

namespace app\common\helper;

/**
 * MIME 类型相关助手类
 * Class Mime
 * @package app\common\helper
 */
final class MimeHelper
{
    const MIME_APPLICATION = 'application';
    const MIME_AUDIO = 'audio';
    const MIME_IMAGE = 'image';
    const MIME_MESSAGE = 'message';
    const MIME_TEXT = 'text';
    const MIME_VIDEO = 'video';
    const MIME_X_WORLD = 'x-world';

    /**
     * 包含了所有RFC中定义的MIME类型和一些常见但是未规定的MIME
     * 1, RFC-822
     * 2, RFC-2045
     * 3, RFC-2046
     * 4, RFC-2047
     * 5, RFC-2048
     * 6, RFC-2049
     * 7, 自定义
     */
    const TYPES = [
        ['extension' => '', 'type' => 'application/octet-stream'],
        ['extension' => '323', 'type' => 'text/h323'],
        ['extension' => '7z', 'type' => 'application/x-compress'],
        ['extension' => 'acx', 'type' => 'application/internet-property-stream'],
        ['extension' => 'ai', 'type' => 'application/postscript'],
        ['extension' => 'aif', 'type' => 'audio/x-aiff'],
        ['extension' => 'aifc', 'type' => 'audio/x-aiff'],
        ['extension' => 'aiff', 'type' => 'audio/x-aiff'],
        ['extension' => 'asf', 'type' => 'video/x-ms-asf'],
        ['extension' => 'asr', 'type' => 'video/x-ms-asf'],
        ['extension' => 'asx', 'type' => 'video/x-ms-asf'],
        ['extension' => 'au', 'type' => 'audio/basic'],
        ['extension' => 'avi', 'type' => 'video/x-msvideo'],
        ['extension' => 'axs', 'type' => 'application/olescript'],
        ['extension' => 'bas', 'type' => 'text/plain'],
        ['extension' => 'bcpio', 'type' => 'application/x-bcpio'],
        ['extension' => 'bin', 'type' => 'application/octet-stream'],
        ['extension' => 'bmp', 'type' => 'image/bmp'],
        ['extension' => 'bz2', 'type' => 'application/x-compress'],
        ['extension' => 'c', 'type' => 'text/plain'],
        ['extension' => 'cat', 'type' => 'application/vnd.ms-pkiseccat'],
        ['extension' => 'cdf', 'type' => 'application/x-cdf'],
        ['extension' => 'cer', 'type' => 'application/x-x509-ca-cert'],
        ['extension' => 'class', 'type' => 'application/octet-stream'],
        ['extension' => 'clp', 'type' => 'application/x-msclip'],
        ['extension' => 'cmx', 'type' => 'image/x-cmx'],
        ['extension' => 'cod', 'type' => 'image/cis-cod'],
        ['extension' => 'cpio', 'type' => 'application/x-cpio'],
        ['extension' => 'crd', 'type' => 'application/x-mscardfile'],
        ['extension' => 'crl', 'type' => 'application/pkix-crl'],
        ['extension' => 'crt', 'type' => 'application/x-x509-ca-cert'],
        ['extension' => 'csh', 'type' => 'application/x-csh'],
        ['extension' => 'css', 'type' => 'text/css'],
        ['extension' => 'dcr', 'type' => 'application/x-director'],
        ['extension' => 'der', 'type' => 'application/x-x509-ca-cert'],
        ['extension' => 'dir', 'type' => 'application/x-director'],
        ['extension' => 'dll', 'type' => 'application/x-msdownload'],
        ['extension' => 'dms', 'type' => 'application/octet-stream'],
        ['extension' => 'doc', 'type' => 'application/msword'],
        ['extension' => 'docx', 'type' => 'application/msword'],
        ['extension' => 'dot', 'type' => 'application/msword'],
        ['extension' => 'dvi', 'type' => 'application/x-dvi'],
        ['extension' => 'dxr', 'type' => 'application/x-director'],
        ['extension' => 'eps', 'type' => 'application/postscript'],
        ['extension' => 'etx', 'type' => 'text/x-setext'],
        ['extension' => 'evy', 'type' => 'application/envoy'],
        ['extension' => 'exe', 'type' => 'application/octet-stream'],
        ['extension' => 'f4v', 'type' => 'application/octet-stream'],
        ['extension' => 'fif', 'type' => 'application/fractals'],
        ['extension' => 'flr', 'type' => 'x-world/x-vrml'],
        ['extension' => 'flv', 'type' => 'application/octet-stream'],
        ['extension' => 'gif', 'type' => 'image/gif'],
        ['extension' => 'gtar', 'type' => 'application/x-gtar'],
        ['extension' => 'gz', 'type' => 'application/x-gzip'],
        ['extension' => 'h', 'type' => 'text/plain'],
        ['extension' => 'hdf', 'type' => 'application/x-hdf'],
        ['extension' => 'hlp', 'type' => 'application/winhlp'],
        ['extension' => 'hqx', 'type' => 'application/mac-binhex40'],
        ['extension' => 'hta', 'type' => 'application/hta'],
        ['extension' => 'htc', 'type' => 'text/x-component'],
        ['extension' => 'htm', 'type' => 'text/html'],
        ['extension' => 'html', 'type' => 'text/html'],
        ['extension' => 'htt', 'type' => 'text/webviewhtml'],
        ['extension' => 'ico', 'type' => 'image/x-icon'],
        ['extension' => 'ief', 'type' => 'image/ief'],
        ['extension' => 'iii', 'type' => 'application/x-iphone'],
        ['extension' => 'ins', 'type' => 'application/x-internet-signup'],
        ['extension' => 'isp', 'type' => 'application/x-internet-signup'],
        ['extension' => 'jfif', 'type' => 'image/pipeg'],
        ['extension' => 'jpe', 'type' => 'image/jpeg'],
        ['extension' => 'jpeg', 'type' => 'image/jpeg'],
        ['extension' => 'jpg', 'type' => 'image/jpeg'],
        ['extension' => 'js', 'type' => 'application/x-javascript'],
        ['extension' => 'latex', 'type' => 'application/x-latex'],
        ['extension' => 'lha', 'type' => 'application/octet-stream'],
        ['extension' => 'lsf', 'type' => 'video/x-la-asf'],
        ['extension' => 'lsx', 'type' => 'video/x-la-asf'],
        ['extension' => 'lzh', 'type' => 'application/octet-stream'],
        ['extension' => 'm13', 'type' => 'application/x-msmediaview'],
        ['extension' => 'm14', 'type' => 'application/x-msmediaview'],
        ['extension' => 'm3u', 'type' => 'audio/x-mpegurl'],
        ['extension' => 'm3u8', 'type' => 'video/x-mpegurl'],
        ['extension' => 'man', 'type' => 'application/x-troff-man'],
        ['extension' => 'mdb', 'type' => 'application/x-msaccess'],
        ['extension' => 'me', 'type' => 'application/x-troff-me'],
        ['extension' => 'mht', 'type' => 'message/rfc822'],
        ['extension' => 'mhtml', 'type' => 'message/rfc822'],
        ['extension' => 'mid', 'type' => 'audio/mid'],
        ['extension' => 'mny', 'type' => 'application/x-msmoney'],
        ['extension' => 'mov', 'type' => 'video/quicktime'],
        ['extension' => 'movie', 'type' => 'video/x-sgi-movie'],
        ['extension' => 'mp2', 'type' => 'video/mpeg'],
        ['extension' => 'mp3', 'type' => 'audio/mpeg'],
        ['extension' => 'mp4', 'type' => 'video/mp4'],
        ['extension' => 'mpa', 'type' => 'video/mpeg'],
        ['extension' => 'mpe', 'type' => 'video/mpeg'],
        ['extension' => 'mpeg', 'type' => 'video/mpeg'],
        ['extension' => 'mpg', 'type' => 'video/mpeg'],
        ['extension' => 'mpp', 'type' => 'application/vnd.ms-project'],
        ['extension' => 'mppx', 'type' => 'application/vnd.ms-project'],
        ['extension' => 'mpv2', 'type' => 'video/mpeg'],
        ['extension' => 'ms', 'type' => 'application/x-troff-ms'],
        ['extension' => 'mvb', 'type' => 'application/x-msmediaview'],
        ['extension' => 'nws', 'type' => 'message/rfc822'],
        ['extension' => 'oda', 'type' => 'application/oda'],
        ['extension' => 'ogg', 'type' => 'video/ogg'],
        ['extension' => 'p10', 'type' => 'application/pkcs10'],
        ['extension' => 'p12', 'type' => 'application/x-pkcs12'],
        ['extension' => 'p7b', 'type' => 'application/x-pkcs7-certificates'],
        ['extension' => 'p7c', 'type' => 'application/x-pkcs7-mime'],
        ['extension' => 'p7m', 'type' => 'application/x-pkcs7-mime'],
        ['extension' => 'p7r', 'type' => 'application/x-pkcs7-certreqresp'],
        ['extension' => 'p7s', 'type' => 'application/x-pkcs7-signature'],
        ['extension' => 'pbm', 'type' => 'image/x-portable-bitmap'],
        ['extension' => 'pdf', 'type' => 'application/pdf'],
        ['extension' => 'pfx', 'type' => 'application/x-pkcs12'],
        ['extension' => 'pgm', 'type' => 'image/x-portable-graymap'],
        ['extension' => 'pko', 'type' => 'application/ynd.ms-pkipko'],
        ['extension' => 'pma', 'type' => 'application/x-perfmon'],
        ['extension' => 'pmc', 'type' => 'application/x-perfmon'],
        ['extension' => 'pml', 'type' => 'application/x-perfmon'],
        ['extension' => 'pmr', 'type' => 'application/x-perfmon'],
        ['extension' => 'pmw', 'type' => 'application/x-perfmon'],
        ['extension' => 'pnm', 'type' => 'image/x-portable-anymap'],
        ['extension' => 'pot', 'type' => 'application/vnd.ms-powerpoint'],
        ['extension' => 'ppm', 'type' => 'image/x-portable-pixmap'],
        ['extension' => 'pps', 'type' => 'application/vnd.ms-powerpoint'],
        ['extension' => 'ppt', 'type' => 'application/vnd.ms-powerpoint'],
        ['extension' => 'pptx', 'type' => 'application/vnd.ms-powerpoint'],
        ['extension' => 'prf', 'type' => 'application/pics-rules'],
        ['extension' => 'ps', 'type' => 'application/postscript'],
        ['extension' => 'pub', 'type' => 'application/x-mspublisher'],
        ['extension' => 'qt', 'type' => 'video/quicktime'],
        ['extension' => 'ra', 'type' => 'audio/x-pn-realaudio'],
        ['extension' => 'ram', 'type' => 'audio/x-pn-realaudio'],
        ['extension' => 'rar', 'type' => 'application/x-compress'],
        ['extension' => 'ras', 'type' => 'image/x-cmu-raster'],
        ['extension' => 'rgb', 'type' => 'image/x-rgb'],
        ['extension' => 'rmi', 'type' => 'audio/mid'],
        ['extension' => 'roff', 'type' => 'application/x-troff'],
        ['extension' => 'rtf', 'type' => 'application/rtf'],
        ['extension' => 'rtx', 'type' => 'text/richtext'],
        ['extension' => 'scd', 'type' => 'application/x-msschedule'],
        ['extension' => 'sct', 'type' => 'text/scriptlet'],
        ['extension' => 'setpay', 'type' => 'application/set-payment-initiation'],
        ['extension' => 'setreg', 'type' => 'application/set-registration-initiation'],
        ['extension' => 'sh', 'type' => 'application/x-sh'],
        ['extension' => 'shar', 'type' => 'application/x-shar'],
        ['extension' => 'sit', 'type' => 'application/x-stuffit'],
        ['extension' => 'snd', 'type' => 'audio/basic'],
        ['extension' => 'spc', 'type' => 'application/x-pkcs7-certificates'],
        ['extension' => 'spl', 'type' => 'application/futuresplash'],
        ['extension' => 'sql', 'type' => 'text/sql'],
        ['extension' => 'src', 'type' => 'application/x-wais-source'],
        ['extension' => 'sst', 'type' => 'application/vnd.ms-pkicertstore'],
        ['extension' => 'stl', 'type' => 'application/vnd.ms-pkistl'],
        ['extension' => 'stm', 'type' => 'text/html'],
        ['extension' => 'svg', 'type' => 'image/svg+xml'],
        ['extension' => 'sv4cpio', 'type' => 'application/x-sv4cpio'],
        ['extension' => 'sv4crc', 'type' => 'application/x-sv4crc'],
        ['extension' => 'swf', 'type' => 'application/x-shockwave-flash'],
        ['extension' => 't', 'type' => 'application/x-troff'],
        ['extension' => 'tar', 'type' => 'application/x-tar'],
        ['extension' => 'tcl', 'type' => 'application/x-tcl'],
        ['extension' => 'tex', 'type' => 'application/x-tex'],
        ['extension' => 'texi', 'type' => 'application/x-texinfo'],
        ['extension' => 'texinfo', 'type' => 'application/x-texinfo'],
        ['extension' => 'tgz', 'type' => 'application/x-compressed'],
        ['extension' => 'tif', 'type' => 'image/tiff'],
        ['extension' => 'tiff', 'type' => 'image/tiff'],
        ['extension' => 'tr', 'type' => 'application/x-troff'],
        ['extension' => 'trm', 'type' => 'application/x-msterminal'],
        ['extension' => 'tsv', 'type' => 'text/tab-separated-values'],
        ['extension' => 'txt', 'type' => 'text/plain'],
        ['extension' => 'uls', 'type' => 'text/iuls'],
        ['extension' => 'ustar', 'type' => 'application/x-ustar'],
        ['extension' => 'vcf', 'type' => 'text/x-vcard'],
        ['extension' => 'vrml', 'type' => 'x-world/x-vrml'],
        ['extension' => 'wav', 'type' => 'audio/x-wav'],
        ['extension' => 'wcm', 'type' => 'application/vnd.ms-works'],
        ['extension' => 'wdb', 'type' => 'application/vnd.ms-works'],
        ['extension' => 'webm', 'type' => 'video/webm'],
        ['extension' => 'wks', 'type' => 'application/vnd.ms-works'],
        ['extension' => 'wmf', 'type' => 'application/x-msmetafile'],
        ['extension' => 'wps', 'type' => 'application/vnd.ms-works'],
        ['extension' => 'wri', 'type' => 'application/x-mswrite'],
        ['extension' => 'wrl', 'type' => 'x-world/x-vrml'],
        ['extension' => 'wrz', 'type' => 'x-world/x-vrml'],
        ['extension' => 'xaf', 'type' => 'x-world/x-vrml'],
        ['extension' => 'xbm', 'type' => 'image/x-xbitmap'],
        ['extension' => 'xla', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xlc', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xlm', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xls', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xlsx', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xlt', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xlw', 'type' => 'application/vnd.ms-excel'],
        ['extension' => 'xof', 'type' => 'x-world/x-vrml'],
        ['extension' => 'xpm', 'type' => 'image/x-xpixmap'],
        ['extension' => 'xwd', 'type' => 'image/x-xwindowdump'],
        ['extension' => 'xz', 'type' => 'application/x-compress'],
        ['extension' => 'z', 'type' => 'application/x-compress'],
        ['extension' => 'zip', 'type' => 'application/zip']
    ];

    /**
     *
     * @param string $extension
     * @return mixed|string
     */
    public static function getTypeByExtension(string $extension = '')
    {
        $result = '';
        foreach (self::TYPES as $type) {
            if ($type['extension'] === $extension) {
                $result = $type['type'];
                break;
            }
        }
        return $result;
    }

    /**
     * 通过文件扩展名获得 MIME 的大类型
     * @param string $extension
     * @return string
     */
    public static function getMainTypeByExtension(string $extension = '')
    {
        $result = '';
        foreach (self::TYPES as $type) {
            if ($type['extension'] === $extension) {
                $vars = explode('/', $type['type']);
                $result = $vars[0];
                break;
            }
        }
        return $result;
    }

    /**
     * 判断某个扩展名是否是某种类型
     * @param string $extension
     * @param string $mime_type
     * @return bool
     */
    public static function isType(string $extension = '', string $mime_type = '')
    {
        $result = false;
        foreach (self::TYPES as $type) {
            if ($type['extension'] === $extension && $type['type'] === $mime_type) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * 判断某个扩展名是否是某种类型
     * @param string $extension
     * @param string $mime_main_type
     * @return bool
     */
    public static function isMainType(string $extension = '', string $mime_main_type = '')
    {
        $result = false;
        foreach (self::TYPES as $type) {
            $vars = explode('/', $type['type']);
            if ($type['extension'] === $extension && $vars[0] === $mime_main_type) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}