<?php

/*
	Author		: AHMET BERK BAŞARAN - https://www.github.com/007basaran/
	Country		: TÜRKİYE
	Version		: 1.0
*/

// Bu fonksiyon gelen bağlantıyı derin bir şekilde inceler, bağlantı yapan ip adresini süzer, ziyaretçi ip adresini bulur ve verir.
function abb_deepdedector()
{
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {  
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"]; 
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"]; 
	}

	$istemci  = @$_SERVER['HTTP_CLIENT_IP'];
	$yonlendirme = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$uzak  = @$_SERVER['REMOTE_ADDR'];
	
	if(filter_var($istemci, FILTER_VALIDATE_IP)) { $ip = $istemci; } elseif(filter_var($yonlendirme, FILTER_VALIDATE_IP)) { $ip = $yonlendirme;} else { $ip = $uzak; }

	return $ip;
}

function abb_vd($abbip=NULL,$tekilsecim=NULL)
{
	
	// Get currently ip address.
	if($abbip==NULL){
		$ip = @abb_deepdedector();
	}else{
		$ip = @$abbip;
	}
	
	// Get remote data with curl, json api.
    $ip_ch = curl_init();
	curl_setopt($ip_ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
	curl_setopt($ip_ch, CURLOPT_HEADER, 0);
	curl_setopt($ip_ch, CURLOPT_RETURNTRANSFER, TRUE);
    @$ip_data_in = curl_exec($ip_ch); 
    curl_close($ip_ch);

    $ip_data = json_decode($ip_data_in,true);
    $ip_data = str_replace('&quot;', '"', $ip_data);
	
	switch($tekilsecim){
		case "ip":
		$vericikisi = @$ip_data['geoplugin_request'];
		break;		
			
		case "city":
		$vericikisi = @$ip_data['geoplugin_city'];
		break;		
		
		case "region":
		$vericikisi = @$ip_data['geoplugin_region'];
		break;
				
		case "regioncode":
		$vericikisi = @$ip_data['geoplugin_regionCode'];
		break;
				
		case "regionname":
		$vericikisi = @$ip_data['geoplugin_regionName'];
		break;
				
		case "areacode":
		$vericikisi = @$ip_data['geoplugin_areaCode'];
		break;
		
		case "dmacode":
		$vericikisi = @$ip_data['geoplugin_dmaCode'];
		break;
		
		case "countrycode":
		$vericikisi = @$ip_data['geoplugin_countryCode'];
		break;
		
		case "countryname":
		$vericikisi = @$ip_data['geoplugin_countryName'];
		break;
				
		case "continentcode":
		$vericikisi = @$ip_data['geoplugin_continentCode'];
		break;
				
		case "continentname":
		$vericikisi = @$ip_data['geoplugin_continentName'];
		break;
		
		case "latitude":
		$vericikisi = @$ip_data['geoplugin_latitude'];
		break;
				
		case "longitude":
		$vericikisi = @$ip_data['geoplugin_longitude'];
		break;
		
		case "locationaccuracyradius":
		$vericikisi = @$ip_data['geoplugin_locationAccuracyRadius'];
		break;
		
		case "timezone":
		$vericikisi = @$ip_data['geoplugin_timezone'];
		break;
		
		case "currencycode":
		$vericikisi = @$ip_data['geoplugin_currencyCode'];
		break;
		
		case "currencysymbol":
		$vericikisi = @$ip_data['geoplugin_currencySymbol_UTF8'];
		break;

		default: 
		$vericikisi = @$ip_data;
	}

    return $vericikisi;
}

?>


<?php 
	
	/* ÖRNEK KULLANIM ŞEKİLLERİ */

	// Özelliğin varsayılan halinde bir dizi geriye döndürürlür, bu dizinin içerisinde şöyle bir liste yer alır : 
	
	/*
		Array
		(
			[geoplugin_request] => 212.154.68.2
			[geoplugin_status] => 200
			[geoplugin_delay] => 1ms
			[geoplugin_credit] => Some of the returned data includes GeoLite data created by MaxMind, available from http://www.maxmind.com.
			[geoplugin_city] => Isparta
			[geoplugin_region] => Isparta
			[geoplugin_regionCode] => 32
			[geoplugin_regionName] => Isparta
			[geoplugin_areaCode] => 
			[geoplugin_dmaCode] => 
			[geoplugin_countryCode] => TR
			[geoplugin_countryName] => Turkey
			[geoplugin_inEU] => 0
			[geoplugin_euVATrate] => 
			[geoplugin_continentCode] => AS
			[geoplugin_continentName] => Asia
			[geoplugin_latitude] => 37.7427
			[geoplugin_longitude] => 30.6906
			[geoplugin_locationAccuracyRadius] => 1000
			[geoplugin_timezone] => Europe/Istanbul
			[geoplugin_currencyCode] => TRY
			[geoplugin_currencySymbol] => ₺
			[geoplugin_currencySymbol_UTF8] => ₺
			[geoplugin_currencyConverter] => 5.8757
		)
	*/
	
	// Bu listeye bu şekilde ulaşmak için sadece fonksiyonu çağırmanız yeterli.
	
	/*
		$cagirilandizi = abb_vd();
		echo '<pre>'; print_r($cagirilandizi); echo '</pre>';
	*/

	// Bu program giriş yapan ip adreslerini otomatik olarak tanımaktadır, farklı bir ip adresini manuel olarak belirlemek isterseniz bu kodu kullanabilirsiniz, böylece girdiğiniz ip adresine ait bilgileri dizi olarak döndürür : 
	
	/*
		$cagirilandizi = abb_vd('212.154.68.2');
		echo '<pre>'; print_r($cagirilandizi); echo '</pre>';
	*/

	
	// Sitenize giriş yapan birinin ip adresinin otomatik algılanmasını ve gelen verilere tekil olarak ulaşmak isterseniz kodlar : 
	
	/*	
		echo abb_vd(null,'ip');
		echo abb_vd(null,'city');
		echo abb_vd(null,'region');
		echo abb_vd(null,'countrycode');
		echo abb_vd(null,'countryname');
		echo abb_vd(null,'currencysymbol');
	*/
	
	// Ziyaretçinin değil de manuel belirleyeceğiniz, rastgele bir ip adresine ait verileri tekil olarak almak isterseniz kod : 
	
	/*	
		echo abb_vd(212.154.68.2,'ip');
		echo abb_vd(212.154.68.2,'city');
		echo abb_vd(212.154.68.2,'region');
		echo abb_vd(212.154.68.2,'countrycode');
		echo abb_vd(212.154.68.2,'countryname');
		echo abb_vd(212.154.68.2,'currencysymbol');
	*/

	// Mevcut php bilginiz doğrultusunda kodlara bakarak, geliştirebilir ya da diğer özellikleri bulabilirsiniz.

	// İyi çalışmalar.

?>
