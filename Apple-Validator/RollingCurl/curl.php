<?php
class curl {
	var $ch, $agent, $error, $info, $cookiefile, $savecookie;	
	function curl() {
		$this->agent = $this->get_agent(rand(0,44));
		$this->ch = curl_init();
		curl_setopt ($this->ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt ($this->ch, CURLOPT_HEADER, 1);
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
//	curl_setopt ($this->ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt ($this->ch, CURLOPT_FOLLOWLOCATION,true);
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, 20);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,20);
		}
	function http_code() {
    	return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
  	}
	function timeout($time){
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, $time);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,$time);
	}
	function error() {
        return curl_error($this->ch);
    }
	function ssl($veryfyPeer, $verifyHost){
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $veryfyPeer);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $verifyHost);
    }
	function header($header) {
		curl_setopt ($this->ch, CURLOPT_HTTPHEADER, $header);
	}	
	function login($user, $pass) {
		curl_setopt ($this->ch, CURLOPT_USERPWD, "$user:$pass");
	}
	function saveImage($url) {
		$page 	=	$this->getPage($url);
		$path 	=	getcwd() . '/captcha.png';
		$fp 		= fopen($path, 'w');
		fwrite($fp, $page);
		fclose($fp);
	}
	function cookies($cookie_file_path) {
		$this->cookiefile = $cookie_file_path;;
		$fp = fopen($this->cookiefile,'wb');fclose($fp);
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
	}
	function ref($ref) {
		curl_setopt ($this->ch, CURLOPT_REFERER,$ref);
	}	
	function socks($sock) {
		curl_setopt ($this->ch, CURLOPT_HTTPPROXYTUNNEL, true); 
		curl_setopt ($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); 
		curl_setopt ($this->ch, CURLOPT_PROXY, $sock);
	}
	function post($url, $data) {
		curl_setopt($this->ch, CURLOPT_POST, 1);	
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		return $this->getPage($url);
	}
	function data($url, $data, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 1);
		curl_setopt ($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		return $this->getPage($url, $hasHeader, $hasBody);
	}
	function get($url, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 0);
		return $this->getPage($url, $hasHeader, $hasBody);
	}	
	function getPage($url, $hasHeader=true, $hasBody=true) {
		curl_setopt($this->ch, CURLOPT_HEADER, $hasHeader ? 1 : 0);
		curl_setopt($this->ch, CURLOPT_NOBODY, $hasBody ? 0 : 1);
		curl_setopt ($this->ch, CURLOPT_URL, $url);
		$data = curl_exec ($this->ch);
		$this->error = curl_error ($this->ch);
		$this->info = curl_getinfo ($this->ch);
		return $data;
	}	
	function close() {
		unlink($this->cookiefile);
		curl_close ($this->ch);
	}
	function get_agent($z){
		switch ($z){
			case 0:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0";	break;
			case 1:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1";	break;
			case 2:	$agent= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";	break;
			case 3:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)";	break;
			case 4:	$agent= "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)";	break;
			case 5:	$agent= "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";	break;
			case 6:	$agent= "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9a8) Gecko/2007100619 GranParadiso/3.0a8";	break;
			case 7:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1b3) Gecko/20090305 Firefox/3.1b3";	break;
			case 8:	$agent= "Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4325)";	break;
			case 9:	$agent= "Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 6.0)";	break;
			case 10:	$agent= "Mozilla/4.0 (compatible; MSIE 5.5b1; Mac_PowerPC)";	break;
			case 11:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";	break;
			case 12:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; .NET CLR 1.1.4322; InfoPath.1; MS-RTC LM 8)";	break;
			case 13:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.0.04506.30)";	break;
			case 14:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; InfoPath.1)";	break;
			case 15:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)";	break;
			case 16:	$agent= "Mozilla/4.0 (compatible; MSIE 6.0; America Online Browser 1.1; rev1.5; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";	break;
			case 17:	$agent= "Mozilla/5.0 (X11; U; Linux; it-IT) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.4 (Change: 413 12f13f8)";	break;
			case 18:	$agent= "Mozilla/5.0 (X11; U; Linux; en-GB) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 239 52c6958)";	break;
			case 19:	$agent= "Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.2 (Change: 189 35c14e0)";	break;
			case 20:	$agent= "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Avant Browser; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)";	break;
			case 21:	$agent= "Mozilla/5.0 (Windows; U; WinNT; en; rv:1.0.2) Gecko/20030311 Beonex/0.8.2-stable";	break;
			case 22:	$agent= "Mozilla/5.0 (X11; U; Linux x86_64; en-GB; rv:1.8.1b1) Gecko/20060601 BonEcho/2.0b1 (Ubuntu-edgy)";	break;
			case 23:	$agent= "Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.4pre) Gecko/20070521 Camino/1.6a1pre";	break;
			case 24:	$agent= "Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/419 (KHTML, like Gecko, Safari/419.3) Cheshire/1.0.ALPHA";	break;
			case 25:	$agent= "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.0.1) Gecko/20021216 Chimera/0.6";	break;
			case 26:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.1 (KHTML, like Gecko) Chrome/2.0.164.0 Safari/530.1";	break;
			case 27:	$agent= "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; InfoPath.2; .NET CLR 2.0.50727; .NET CLR 1.1.4322; Crazy Browser 3.0.0 Beta2)";	break;
			case 28:	$agent= "Mozilla/5.0 (X11; U; Linux i686; en; rv:1.8.1.12) Gecko/20080208 (Debian-1.8.1.12-2) Epiphany/2.20";	break;
			case 29:	$agent= "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1b2pre) Gecko/20081015 Fennec/1.0a1";	break;
			case 30:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.6b) Gecko/20031212 Firebird/0.7+";	break;
			case 31:	$agent= "Mozilla/5.0 (X11; U; Linux i686; it-IT; rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.04 (jaunty) Firefox/3.5";	break;
			case 32:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9b3) Gecko/2008020514 Firefox/3.0b3";	break;
			case 33:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 6.0; it; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9";	break;
			case 34:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1.5) Gecko/20070713 Firefox/2.0.0.5";	break;
			case 35:	$agent= "Mozilla/4.76 [en] (X11; U; Linux 2.4.9-34 i686)";	break;
			case 36:	$agent= "Mozilla/4.75 [fr] (WinNT; U)";	break;
			case 37:	$agent= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Opera 7.52 [en]";	break;
			case 38:	$agent= "Mozilla/4.0 (compatible; MSIE 6.0; ; Linux i686) Opera 7.50 [en]";	break;
			case 39:	$agent= "Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.5; en-US; rv:1.9.1b3pre) Gecko/20081212 Mozilla/5.0 (Windows; U; Windows NT 5.1; en) AppleWebKit/526.9 (KHTML, like Gecko) Version/4.0dp1 Safari/526.8";	break;
			case 40:	$agent= "Mozilla/5.0 (X11; U; Linux i686; de-AT; rv:1.8.0.2) Gecko/20060309 SeaMonkey/1.0";	break;
			case 41:	$agent= "Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.7.6) Gecko/20050405 Epiphany/1.6.1 (Ubuntu) (Ubuntu package 1.0.2)";	break;
			case 42:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.5) Gecko/20060731 Firefox/1.5.0.5 Flock/0.7.4.1";	break;			
			case 43:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/0.2.153.1 Safari/525.19 ";	break;
			case 44:	$agent= "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9b5) Gecko/2008032620 Firefox/3.0b5 ";	break;
		}
		return $agent;
	}
}
function xflush() {
 
    static $output_handler = null;
 
    if ($output_handler === null) {
 
        $output_handler = @ini_get('output_handler');
 
    }
 
 
 
    if ($output_handler == 'ob_gzhandler') {
 
        return;
 
    }
 
 
 
    flush();
 
    if (function_exists('ob_flush') AND function_exists('ob_get_length') AND ob_get_length() !== false) {
 
        @ob_flush();
 
    } else if (function_exists('ob_end_flush') AND function_exists('ob_start') AND function_exists('ob_get_length') AND ob_get_length() !== FALSE) {
 
        @ob_end_flush();
 
        @ob_start();
 
    }
 
}

function inStr($s, $as){
$s = strtoupper($s);
if(!is_array($as)) $as=array($as);
for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true;
return false;
}
function getStr($string,$start,$end){
    $str = explode($start,$string);
    $str = explode($end,$str[1]);
    return $str[0];
}
function fetch_value($str,$find_start,$find_end) {
$start = @strpos($str,$find_start);
if ($start === false) {
return "";
}
$length = strlen($find_start);
$end    = strpos(substr($str,$start +$length),$find_end);
return trim(substr($str,$start +$length,$end));
}
?>