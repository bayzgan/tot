<?php  
error_reporting(0);
function post($url, $data, $headers = null, $proxy = null) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

	if ($proxy != "") {
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);	
	}

	if ($headers != "" ) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function string($length) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function register($name, $proxy) {
	$domain = array('postfach2go.de', 'mailbox2go.de', 'briefkasten2go.de', 'email-paul.de', 'schreib-mir.tk');
	$rand = array_rand($domain);
	$email = string(10).'@'.$domain[$rand];

	$url = 'https://sea-m.newchic.com/en/api/account/registernew/';
	$data = 'email='.$email.'&pwd=yudha123&re_pwd=yudha123&regcaptcha=';

	// taro cookies disini
	$cookies = 'route=dfe472f06455f52c5b225bcba60bf0a3; XSRF-TOKEN=eyJpdiI6ImxWV1k0dEgxcVpteUIzXC9NbDRvNG1RPT0iLCJ2YWx1ZSI6ImN1TGE2Q0RUbytPZHF4UW5sQ1Y3UVhpN1pVblB0MTFMYW9RQUlMWmlQM1ljS2phV0N5RnQ5YVZhWGY0R0UzQ0JBYmNhVFNKM0hydEs1TXJlbWdhSWlBPT0iLCJtYWMiOiJmYTlmMjBmZjAwYTg0NDgwNzAxNTYyM2NjNTUzNTEyYjAwMmRhNmFiZjE2Y2ExNDk5ODIyM2FjODNhMmNiMmM0In0%3D; laravel_session=eyJpdiI6InBlNUUybnpSM3A3WlZzaG9kVTFGeEE9PSIsInZhbHVlIjoiWWw0UmtaU2VTc1wvazBZbDg1QW9RVUEwNkJ6cnVvRjhHK1ZuNXdXQTJFalVqbUtiV0xFVFwvR05qRFdNQm9UZlc2UzI1RDZSbE1GRUFqM25SaFJUTkxLdz09IiwibWFjIjoiODE5OTBhYjJjNmQ0N2ZkYTNmZTc0ZWQ4MTE1MjQzZGE5YjFiY2MzNWVkZTRjNDk5ODNkODY5YWY5NTZhZTY5ZCJ9"';

	$headers = array(
		'Origin: https://sea-m.newchic.com',
		'Accept-Language: en-US,en;q=0.9',
		'User-Agent: Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Mobile Safari/537.36',
		'Content-Type: application/x-www-form-urlencoded',
		'Accept: application/json, text/plain, */*',
		''.$cookies.'',
		'Connection: keep-alive',
	);

	$register = post($url, $data, $headers, $proxy);

	if (strpos($register, '"message":"succeed"')) {
		echo $proxy." | Success register | Email: ".$email."\n";
		// Save result email
		$data =  $email."\r\n";
		$fh = fopen("list_email_".$name.".txt", "a");
		fwrite($fh, $data);
		fclose($fh);
	} elseif (strpos($register, '"message":"Register frequently, please enter verification code."')) {
		echo $proxy." | Captcha detected\n";
	} else {
		echo $proxy." | Proxy die\n";
	}
}


echo "Newchic Referral\n";
echo "Created by yudha tira pamungkas\n";
echo "Facebook: https://facebook.com/yudha.t.pamungkas.3\n\n";

echo "Proxy list (Ex: proxy.txt): ";
$proxy = trim(fgets(STDIN));

if ($proxy == "") {
	die("Cannot be blank!\n");
} else {
	$file = file_get_contents($proxy) or die ($proxy." not found!\n");
	$explode = explode("\n", $file);
	echo "Total proxy: ".count($explode)."\n";
	$name = string(8);
	
	foreach ($explode as $prxy) {
		register($name, $prxy);
	}
}

?>