<?php 
function get_monto_max()
{
    $monto = Config::get('monto-maximo'); 
    return ($monto / 20000);
}

function get_front_name()
{
    $user = $_SESSION['front']['userdata'];
    
    return $user['first_name'].' '.$user['middle_name'].' '.$user['last_name'].' '.$user['second_last_name'];
}

function fix_double_encoding($string)
{
    $utf8_chars = explode(' ', 'À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö × Ø Ù Ú Û Ü Ý Þ ß à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö');
    $utf8_double_encoded = array();
    foreach($utf8_chars as $utf8_char)
    {
            $utf8_double_encoded[] = utf8_encode(utf8_encode($utf8_char));
    }
    $string = str_replace($utf8_double_encoded, $utf8_chars, $string);
    return $string;
}

function redirect_https()
{
    /* Apache */ 
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 1)
    {
        $ssl = true;
        /* IIS */ 
    }
    elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') 
    {
        $ssl = true;
        /* puertos */
    }
    elseif($_SERVER['SERVER_PORT'] == 443)
    {
        $ssl = true;
    } 
    else 
    {
        $ssl = false;
    }
    
    if ($ssl === false AND Config::get('devel') !== true)
    {
        redirect("https://www.".preg_replace("/^www./", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
    }
}

function get_application_name($app = NULL)
{
    if ( ! $app)
    {
        return FALSE;
    }
    
    return $app->first_name.' '.$app->middle_name.' '.$app->last_name.' '.$app->second_last_name;
}

/*
 * Adds 1 bussiness day to a specified date
 * 
 * @param string $date specified date to add one bussiness date to.
 * @return string returns the new date with the added date
 */

function next_bussiness_day($date = '')
{
    if ( ! $date)
    {
        $date = today();
    }
    
    $day  = date('w', strtotime($date));
    $days = 0;
        
    switch ($day)
    {
        case 5:
            $days = 3;
            break;
        case 6:
            $days = 2;
            break;
        default:
            $days = 1;
            break;
    }
    
    return add_days($days, $date);
}

/**
 * Add days to a date.
 *
 * @param string $d, date
 * @param int $ndays, number of days
 * @param string $format, format date (@see: http://php.net/manual/en/function.date.php)
 */
function add_days($ndays, $d, $format="Y-m-d") {
    $p = $ndays > 1 ? "s" : "";
    $nf = strtotime(date($format, strtotime($d)) . " +".$ndays." day". $p);

    return date($format, $nf);
}

function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
  $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

function get_age( $p_strDate ) 
{
    list($Y,$m,$d) = explode("-",$p_strDate);
    $years         = date("Y") - $Y;
    
    if ( date("md") < $m.$d ) 
    { 
        $years--; 
    }
    return $years;
}

function ieversion() 
{
    $match = preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$reg);
    
    if ($match == 0)
    {
        return -1;
    }
    
    return floatval($reg[1]);
}

function salt_it($str = '')
{
    if($str == ''){
        return FALSE;
    }
    $salt = item('salt');
    $salt = md5(sha1($salt));
    $str  = sha1(md5($str));
    $str  = md5(sha1(md5($salt . $str . $salt)));
    return $str;
}


function get_rand_id($length)
{
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
    $validCharNumber = strlen($validCharacters);
 
    $result = "";
 
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
 
    return $result;
}


function send_mail($email = '' , $name = '', $subject = '', $alt = '', $body = '')
{
    if ($subject == '' OR $name == '' OR $email == '' OR $body == '')
    {
       return;
    }
    
    
    $cabeceras  = 'MIME-Version: 1.0' . "\n";
    $cabeceras .= "Content-Disposition: inline\n";
    $cabeceras .= 'Content-Transfer-Encoding: 8bit' . "\n";
    $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\n";
    $cabeceras .= "X-Mailer: Prestamigo\n";
    $cabeceras .= 'From: Prestamigo.com <servicioalcliente@prestamigo.com>' . "\n";
    $cabeceras .= 'Return-Path: <servicioalcliente@prestamigo.com>' . "\n";
    
    if ( ! is_array($email))
    {
        $email = array($email);
    }
    
    foreach ($email as $mail)
    {
        mail($mail, $subject, $body, $cabeceras, '-f servicioalcliente@prestamigo.com');
    }
    //debug($cabeceras);
    /*
    if ($this->acl->id == 1)
    {   
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $cabeceras .= "X-Mailer: Prestamigo \r\n"; 
        $cabeceras .= 'From: Prestamigo.com <servicioalcliente@prestamigo.com>' . "\r\n";
        $cabeceras .= 'Return-Path: <servicioalcliente@prestamigo.com>' . "\r\n";
        
        mail(Config::get('admin_email'), $subject, $body, $cabeceras, '-f servicioalcliente@prestamigo.com');        
    }*/
    return;
}

    
    function diff($d1, $d2)
    {
        $d1 = (is_string($d1) ? strtotime($d1) : $d1);
        $d2 = (is_string($d2) ? strtotime($d2) : $d2);
    
        $diff_secs = abs($d1 - $d2);
        $base_year = min(date("Y", $d1), date("Y", $d2));
    
        $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
        
        return array(
            "years"        => date("Y", $diff) - $base_year,
            "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
            "months"       => date("n", $diff) - 1,
            "days_total"   => floor($diff_secs / (3600 * 24)),
            "days"         => date("j", $diff) - 1,
            "hours_total"  => floor($diff_secs / 3600),
            "hours"        => date("G", $diff),
            "minutes_total"=> floor($diff_secs / 60),
            "minutes"      => (int) date("i", $diff),
            "seconds_total"=> $diff_secs,
            "seconds"      => (int) date("s", $diff)
        );
    }
    
function countdays($start = '', $end = '')
{
    $start = strip_hour($start);
    $end   = strip_hour($end);
    
    $start_ts = strtotime($start);    
    $end_ts = strtotime($end);    
    $diff = $end_ts - $start_ts;
    
    return round($diff / 86400);
}

function days_from_today($end)
{
    return countdays(today(), $end);
}
    
function due_date($sent_date = '', $days = '')
{
    return strip_hour(add_days($days, $sent_date));
}


    
    function item($item = null)
    {
        include(RUTA_CONFIG . 'config' .EXT);
        return $config[$item];
    }
    
    function create_hash($id = '')
    {
        return sha1(md5(sha1($id . date('Y-m-d h:i:s a') )));
    }
    

    
    function reverse_strrchr($haystack, $needle)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr($haystack, 0, $pos );
    }
    
    function format_size($size)
    {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[$i]); }
    }
    
    function array_cmp($a, $b)
    {
        $al = strtolower($a->consecutivo);
        $bl = strtolower($b->consecutivo);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    function clean_downloads_name($name = '')
    {
        if($name == ''){
            return;
        }
        $ext = strrchr($name,'.');
        $name = reverse_strrchr($name,'.');
        $name = reverse_strrchr($name,'-');
        return $name . $ext;
    }
    
    function files_identical($fn1, $fn2) {
        if(filetype($fn1) !== filetype($fn2))
            return FALSE;
    
        if(filesize($fn1) !== filesize($fn2))
            return FALSE;
    
        if(!$fp1 = fopen($fn1, 'rb'))
            return FALSE;
    
        if(!$fp2 = fopen($fn2, 'rb')) {
            fclose($fp1);
            return FALSE;
        }
    
        $same = TRUE;
        while (!feof($fp1) and !feof($fp2))
            if(fread($fp1, READ_LEN) !== fread($fp2, READ_LEN)) {
                $same = FALSE;
                break;
            }
    
        if(feof($fp1) !== feof($fp2))
            $same = FALSE;
    
        fclose($fp1);
        fclose($fp2);
    
        return $same;
    }
    
  function objSort(&$objArray,$indexFunction,$sort_flags=0) {
    $indices = array();
    foreach($objArray as $obj) {
        $indeces[] = $indexFunction($obj);
    }
    return array_multisort($indeces,$objArray,$sort_flags);
}

function getIndex($obj) {
    return $obj->getPosition();
}


  
function formato_monto($monto = 0)
{
    $sign = '';     
    if (strpos($monto, '-') !== FALSE)
    {
        $sign = '-';
        $monto = str_replace('-', '', $monto);
    }
    //€
    return $sign.'$'.number_format($monto);
}
    
    
   function _uri($segment = 1)
   {
       return Uri::getInstance()->get($segment);
   }

   if ( ! function_exists('random_string'))
{
    function random_string($type = 'alnum', $len = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alnum'    :
            case 'numeric'  :
            case 'nozero'   :
            case 'alpha'    :

                    switch ($type)
                    {
                        case 'alpha'    :   $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'alnum'    :   $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'numeric'  :   $pool = '0123456789';
                            break;
                        case 'nozero'   :   $pool = '123456789';
                            break;
                    }

                    $str = '';
                    for ($i=0; $i < $len; $i++)
                    {
                        $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                    }
                    return $str;
                break;
            case 'unique'   :
            case 'md5'      :

                        return md5(uniqid(mt_rand()));
                break;
            case 'encrypt'  :
            case 'sha1' :

                        $CI =& get_instance();
                        $CI->load->helper('security');

                        return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
                break;
        }
    }
}

function seems_utf8($str) 
{
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    if (seems_utf8($string)) {
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
        // Euro Sign
        chr(226).chr(130).chr(172) => 'E',
        // GBP (Pound) Sign
        chr(194).chr(163) => '');

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
            .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
            .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
            .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
            .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
            .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
            .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
            .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
            .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
            .chr(252).chr(253).chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

function to_upper_no_accents($value = '')
{
    return strtoupper(remove_accents($value));
}
    
/* End of file helpers.phtml */
/* Location: /includes/helpers.phtml */
