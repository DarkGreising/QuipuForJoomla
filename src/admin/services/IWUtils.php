<?php

defined('_JEXEC') or die('Restricted access');


/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Cajón de sastre para métodos útiles.
 */
class IWUtils {

    private static $_locale = false;

    public static function getLocale() {
        if (!is_string(self::$_locale)) {
            //$loc = JFactory::getLanguage()->getLocale();
            //self::$_locale = $loc[0];
            $loc = JFactory::getLanguage()->getTag();
            self::$_locale = str_replace('-', '_', $loc);
        }

        return self::$_locale;
    }

    /**
     * 
     * @param type $num
     * @param type $addSimbol
     * @return type
     */
    public static function fmtEuro($num, $addSymbol = true) {
        $use_mf = function_exists('money_format');
        $locale = self::getLocale();
        setlocale(LC_MONETARY, $locale);
        $symbol = IWConfig::getInstance()->currency_symbol;
        $value = $use_mf ? money_format('%!i', $num) : number_format($num, 2);
        if ($addSymbol) {
            if ($symbol) {
                $value.=" $symbol";
            } else {
                $value = $use_mf ? money_format('%i', $num) : number_format($num, 2);
            }
        }
        return $value;
        /*
          $v = number_format($num, 2, ',', '.');
          if($addSimbol){
          $v .= '€';
          }
          return $v; */
    }

    /**
     * Elimina los atributos style de todos los elementos.
     * 
     * @param type $html 
     */
    public static function filterStyles($html, $isDocument = false) {
        $path = JPATH_COMPONENT . DS . 'lib' . DS . 'QueryPath';
        require_once($path . DS . 'QueryPath.php');
        if (!$isDocument) {
            $html = "<html><head></head><body>$html</body></html>";
        }
        $allowed = array('color', 'background-color');

        $html = qp($html)
                ->find('*')
                ->removeAttr('style')
                ->find('body')
                ->innerHTML();

        return $html;
    }

    public static function sumarize($text, $wordsCount = 5) {
        $text = trim($text);

        if (!$text)
            return ' ';
        $words = explode(' ', $text);
        if (count($words) < $wordsCount)
            return $text;

        $summary = array_slice($words, 0, $wordsCount);
        return implode(' ', $summary) . ' ...';
    }

    /**
     *
     * @param type $input 
     */
    public static function txt2HTML($text) {
        $text = self::htmlEscapeAndLinkUrls($text);
        $text = self::breaksToParagraphs($text);

        return $text;
    }

    /**
     *
     * @param type $text 
     */
    public static function breaksToParagraphs($text) {
        $ps = explode("\n", $text);
        $output = '';
        foreach ($ps as $p) {
            $output.="<p>$p</p>";
        }
        return $output;
    }

    /**
     *
     * @param type $txt
     * @return type 
     */
    public static function singulariza($word) {
        $rules = array(
            'ores' => 'or',
            'oras' => 'ora',
            'as' => 'a',
            'es' => 'e',
            'is' => 'i',
            'os' => 'o',
        );
        foreach (array_keys($rules) as $key) {
            if (substr($word, (strlen($key) * -1)) != $key)
                continue;
            if ($key === false)
                return $word;
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }
        return $word;
    }

    /**
     *
     * @param type $txt 
     */
    public static function separaPalabras($txt) {
        $parts = explode('_', $txt);
        $output = '';
        foreach ($parts as $part) {
            $output.=ucfirst($part) . ' ';
        }
        return rtrim($output);
    }

    /*
     *    $files is an array of full file paths
     *    $destination is a full path to where the zip file will go
     */
    /* creates a compressed zip file */

    public static function zip($files, $destination = '') {
        jimport('joomla.filesystem.archive');
        $zip_adapter = JArchive::getAdapter('zip'); // compression type
        $filesToZip = array();
        foreach ($files as $file) {
            $data = IWFile::read($file);
            $filesToZip[] = array('name' => IWFile::getName($file), 'data' => $data);
        }
        if (!$zip_adapter->create($destination, $filesToZip, array())) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_Ha ocurrido un error al crear el archivo.'), 'message');
            return false;
        }
        return true;
    }

    /**
     *
     * @param type $filename
     * @param type $retbytes
     * @return type 
     */
    public static function readfile_chunked($filename, $retbytes = true) {
        ob_clean();
        flush();
        $chunksize = 1 * (1024 * 1024); // how many bytes per chunk
        $buffer = '';
        $cnt = 0;
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            @ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

    /**
     *
     * @param type $file
     * @param type $contentType
     * @param type $inline 
     */
    public static function downloadFile($file, $filename = false, $contentType = 'application/octet-stream', $inline = FALSE, $exit = true, $removeFile = false) {
        if (headers_sent()) {
            JError::raiseError(500, 'Some data has already been output to browser, can\'t send PDF file');
        }
        if (!file_exists($file)) {
            JError::raiseError(500, 'File not found :-(');
        }
        if (!$filename) {
            $filename = IWFile::getName($file);
        }
        $bytes = filesize($file);

        // Required for some browsers 
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header("Pragma: public"); // required 
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . $filename . '"');
        header("Content-Transfer-Encoding: binary");
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header("Content-Length: " . $bytes);
        if (!ini_get('safe_mode')) { // set_time_limit doesn't work in safe mode
            @set_time_limit(0);
        }
        //readfile($ruta);
        self::readfile_chunked($file);

        if ($removeFile) {
            unlink($file);
        }
        if ($exit) {
            exit();
        }
    }

    /**
     * 
     * @param type $file
     */
    public static function getFileMimeType($file) {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $finfo = new finfo(FILEINFO_MIME);

            $type = $finfo->file($file);
            
            return $type;
        } else {
            return mime_content_type($file);
        }
    }

    /**
     *
     * @param type $a
     * @return type 
     */
    public static function array_average($a) {
        return array_sum($a) / count($a);
    }

    /**
     * https://bitbucket.org/kwi/urllinker/src
     * 
     *  UrlLinker - facilitates turning plain text URLs into HTML links.
     *
     *  Author: SÃ¸ren LÃ¸vborg
     *
     *  To the extent possible under law, SÃ¸ren LÃ¸vborg has waived all copyright
     *  and related or neighboring rights to UrlLinker.
     *  http://creativecommons.org/publicdomain/zero/1.0/
     */

    /**
     *  Transforms plain text into valid HTML, escaping special characters and
     *  turning URLs into links.
     */
    public static function htmlEscapeAndLinkUrls($text) {
        /*
         *  Regular expression bits used by htmlEscapeAndLinkUrls() to match URLs.
         */

        $rexProtocol = '(https?://)?';
        $rexDomain = '(?:[-a-zA-Z0-9]{1,63}\.)+[a-zA-Z][-a-zA-Z0-9]{1,62}';
        $rexIp = '(?:[1-9][0-9]{0,2}\.|0\.){3}(?:[1-9][0-9]{0,2}|0)';
        $rexPort = '(:[0-9]{1,5})?';
        $rexPath = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexUsername = '[^]\\\\\x00-\x20\"(),:-<>[\x7f-\xff]{1,64}';
        $rexPassword = $rexUsername; // allow the same characters as in the username
        $rexUrl = "$rexProtocol(?:($rexUsername)(:$rexPassword)?@)?($rexDomain|$rexIp)($rexPort$rexPath$rexQuery$rexFragment)";
        $rexUrlLinker = "{\\b$rexUrl(?=[?.!,;:\"]?(\s|$))}";

        /**
         *  $validTlds is an associative array mapping valid TLDs to the value true.
         *  Since the set of valid TLDs is not static, this array should be updated
         *  from time to time.
         *
         *  List source:  http://data.iana.org/TLD/tlds-alpha-by-domain.txt
         *  Last updated: 2011-10-09
         */
        $validTlds = array_fill_keys(explode(" ", ".ac .ad .ae .aero .af .ag .ai .al .am .an .ao .aq .ar .arpa .as .asia .at .au .aw .ax .az .ba .bb .bd .be .bf .bg .bh .bi .biz .bj .bm .bn .bo .br .bs .bt .bv .bw .by .bz .ca .cat .cc .cd .cf .cg .ch .ci .ck .cl .cm .cn .co .com .coop .cr .cu .cv .cx .cy .cz .de .dj .dk .dm .do .dz .ec .edu .ee .eg .er .es .et .eu .fi .fj .fk .fm .fo .fr .ga .gb .gd .ge .gf .gg .gh .gi .gl .gm .gn .gov .gp .gq .gr .gs .gt .gu .gw .gy .hk .hm .hn .hr .ht .hu .id .ie .il .im .in .info .int .io .iq .ir .is .it .je .jm .jo .jobs .jp .ke .kg .kh .ki .km .kn .kp .kr .kw .ky .kz .la .lb .lc .li .lk .lr .ls .lt .lu .lv .ly .ma .mc .md .me .mg .mh .mil .mk .ml .mm .mn .mo .mobi .mp .mq .mr .ms .mt .mu .museum .mv .mw .mx .my .mz .na .name .nc .ne .net .nf .ng .ni .nl .no .np .nr .nu .nz .om .org .pa .pe .pf .pg .ph .pk .pl .pm .pn .pr .pro .ps .pt .pw .py .qa .re .ro .rs .ru .rw .sa .sb .sc .sd .se .sg .sh .si .sj .sk .sl .sm .sn .so .sr .st .su .sv .sy .sz .tc .td .tel .tf .tg .th .tj .tk .tl .tm .tn .to .tp .tr .travel .tt .tv .tw .tz .ua .ug .uk .us .uy .uz .va .vc .ve .vg .vi .vn .vu .wf .ws .xn--0zwm56d .xn--11b5bs3a9aj6g .xn--3e0b707e .xn--45brj9c .xn--80akhbyknj4f .xn--90a3ac .xn--9t4b11yi5a .xn--clchc0ea0b2g2a9gcd .xn--deba0ad .xn--fiqs8s .xn--fiqz9s .xn--fpcrj9c3d .xn--fzc2c9e2c .xn--g6w251d .xn--gecrj9c .xn--h2brj9c .xn--hgbk6aj7f53bba .xn--hlcj6aya9esc7a .xn--j6w193g .xn--jxalpdlp .xn--kgbechtv .xn--kprw13d .xn--kpry57d .xn--lgbbat1ad8j .xn--mgbaam7a8h .xn--mgbayh7gpa .xn--mgbbh1a71e .xn--mgbc0a9azcg .xn--mgberp4a5d4ar .xn--o3cw4h .xn--ogbpf8fl .xn--p1ai .xn--pgbs0dh .xn--s9brj9c .xn--wgbh1c .xn--wgbl6a .xn--xkc2al3hye2a .xn--xkc2dl3a5ee0h .xn--yfro4i67o .xn--ygbi2ammx .xn--zckzah .xxx .ye .yt .za .zm .zw"), true);



        $html = '';

        $position = 0;
        while (preg_match($rexUrlLinker, $text, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($url, $urlPosition) = $match[0];

            // Add the text leading up to the URL.
            $html .= htmlspecialchars(substr($text, $position, $urlPosition - $position));

            $protocol = $match[1][0];
            $username = $match[2][0];
            $password = $match[3][0];
            $domain = $match[4][0];
            $afterDomain = $match[5][0]; // everything following the domain
            $port = $match[6][0];
            $path = $match[7][0];

            // Check that the TLD is valid or that $domain is an IP address.
            $tld = strtolower(strrchr($domain, '.'));
            if (preg_match('{^\.[0-9]{1,3}$}', $tld) || isset($validTlds[$tld])) {
                // Do not permit implicit protocol if a password is specified, as
                // this causes too many errors (e.g. "my email:foo@example.org").
                if (!$protocol && $password) {
                    $html .= htmlspecialchars($username);

                    // Continue text parsing at the ':' following the "username".
                    $position = $urlPosition + strlen($username);
                    continue;
                }

                if (!$protocol && $username && !$password && !$afterDomain) {
                    // Looks like an email address.
                    $completeUrl = "mailto:$url";
                    $linkText = $url;
                } else {
                    // Prepend http:// if no protocol specified
                    $completeUrl = $protocol ? $url : "http://$url";
                    $linkText = "$domain$port$path";
                }

                $linkHtml = '<a href="' . htmlspecialchars($completeUrl) . '">'
                        . htmlspecialchars($linkText)
                        . '</a>';

                // Cheap e-mail obfuscation to trick the dumbest mail harvesters.
                $linkHtml = str_replace('@', '&#64;', $linkHtml);

                // Add the hyperlink.
                $html .= $linkHtml;
            } else {
                // Not a valid URL.
                $html .= htmlspecialchars($url);
            }

            // Continue text parsing from after the URL.
            $position = $urlPosition + strlen($url);
        }

        // Add the remainder of the text.
        $html .= htmlspecialchars(substr($text, $position));
        return $html;
    }

    /**
     *
     * @param type $i
     * @param type $n
     * @param type $sat
     * @param type $br
     * @return type 
     */
    public static function random_color($i = null, $n = 10, $sat = .5, $br = .7) {
        $i = is_null($i) ? mt_rand(0, $n) : $i;
        $rgb = self::hsv2rgb(array($i * (360 / $n), $sat, $br));
        for ($k = 0; $k <= 2; $k++)
            $rgb[$k] = dechex(ceil($rgb[$k]));
        return implode('', $rgb);
    }

    /**
     *
     * @param type $c
     * @return type 
     */
    public static function hsv2rgb($c) {
        list($h, $s, $v) = $c;
        if ($s == 0)
            return array($v, $v, $v);
        else {
            $h = ($h%=360) / 60;
            $i = floor($h);
            $f = $h - $i;
            $q[0] = $q[1] = $v * (1 - $s);
            $q[2] = $v * (1 - $s * (1 - $f));
            $q[3] = $q[4] = $v;
            $q[5] = $v * (1 - $s * $f);
            return(array($q[($i + 4) % 6] * 255, $q[($i + 2) % 6] * 255, $q[$i % 6] * 255)); //[1] 
        }
    }

    /**
     * http://www.justin-cook.com/wp/2006/08/02/php-insert-into-an-array-at-a-specific-position/
     * @param type $array
     * @param type $insert
     * @param type $position
     * @return boolean 
     */
    public static function array_insert(&$array, $insert, $position = -1) {
        $position = ($position == -1) ? (count($array)) : $position;
        if ($position != (count($array))) {
            $ta = $array;
            for ($i = $position; $i < (count($array)); $i++) {
                if (!isset($array[$i])) {
                    die(print_r($array, 1) . "\r\nInvalid array: All keys must be numerical and in sequence.");
                }
                $tmp[$i + 1] = $array[$i];
                unset($ta[$i]);
            }
            $ta[$position] = $insert;
            $array = $ta + $tmp;
            //print_r($array);
        } else {
            $array[$position] = $insert;
        }

        ksort($array);
        return true;
    }

    /**
     * 
     * @param type $str
     * @return int
     */
    public static function parseFloat($str) {
        if (is_string($str)) {
            $str = str_replace(',', '.', $str);
            $str = (float) $str;
        }
        return $str;
    }

    /**
     *
     * @param type $input
     * @return type 
     */
    public static function validFolderName($input) {
        $fld = IWFile::makeSafe(strtolower(trim($input)));
        $fld = str_replace(' ', '-', $fld);
        $fld = str_replace('á', 'a', $fld);
        $fld = str_replace('é', 'e', $fld);
        $fld = str_replace('í', 'i', $fld);
        $fld = str_replace('ó', 'o', $fld);
        $fld = str_replace('ú', 'u', $fld);
        $fld = str_replace('ñ', 'n', $fld);
        return $fld;
    }

}

