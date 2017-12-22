<?php
	
	// Ruhum
	// 23.12.2017
	
	function Kalitesi($iTag){

		switch ($iTag) {
			case 5:
				$Mesaj = 'Flv (400x240)';
				break;
			
			case 6:
				$Mesaj = 'Flv (450x270)';
				break;
			
			case 13:
				$Mesaj = '3gp';
				break;
			
			case 17:
				$Mesaj = '3gp (176x144)';
				break;
			
			case 18:
				$Mesaj = 'Mp4 (640x360)';
				break;
			
			case 22:
				$Mesaj = 'HD - Mp4 (1280x720)';
				break;
			
			case 34:
				$Mesaj = '360p Flv - 640x360';
				break;
			
			case 35:
				$Mesaj = '480p Flv - 854x480';
				break;
			
			case 36:
				$Mesaj = '3gp - 320x240';
				break;
			
			case 37:
				$Mesaj = 'HD 1080p - Mp4 (1920x1080)';
				break;
			
			case 38:
				$Mesaj = 'HD 3072p - Mp4 (4096x3072)';
				break;
			
			case 43:
				$Mesaj = '360p - Webm (640x360)';
				break;
			
			case 44:
				$Mesaj = '480p - Webm (854x480)';
				break;
			
			case 45:
				$Mesaj = '720p - Webm (1280x720)';
				break;
			
			case 46:
				$Mesaj = '1080p - Webm (1920x1080)';
				break;
			
			default:
				$Mesaj = 'Bulunamadı';
				break;

		}

		return $Mesaj;

	}

	function GetVideoSourceUrl($Baglanti){

		$YtVideoID = explode('v=', $Baglanti);
		$YtVideoID = end($YtVideoID);
		$YtVideoID = substr($YtVideoID, 0, 11);

		$Baglantilar = array();
		$Baslik = '';
		$Kaynak = file_get_contents('http://www.youtube.com/get_video_info?&video_id='.$YtVideoID.'&hl=tr');
		parse_str($Kaynak,$Sonuclar);

		$Baslik = $Sonuclar['title'];
		$Sonuclar['url_encoded_fmt_stream_map'] = isset($Sonuclar['url_encoded_fmt_stream_map'])?$Sonuclar['url_encoded_fmt_stream_map']:false;
		
		if($Sonuclar['url_encoded_fmt_stream_map']){

			$UrlBilgiler = explode(',',$Sonuclar['url_encoded_fmt_stream_map']);

			foreach($UrlBilgiler as $Bilgi){

				parse_str($Bilgi,$VideoBilgileri);

				$VideoUrl = urldecode($VideoBilgileri['url']);
				$Baglantilar[] = '<a href="'.$VideoUrl.'">'.Kalitesi($VideoBilgileri['itag']).'</a>';

			}

		}

		return array($Baslik, $Baglantilar);

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Get Youtube Video Source Links</title>
	<link rel="stylesheet" type="text/css" href="Style.css" />
</head>
<body>

<?php
	
	if(isset($_POST['TwitterProfileUrl'])){

?>
	<center><h2>Get Youtube Video Source Links</h2></center>

	<div class="Duzen">
		
		<div class="GenelBilgiler">
			<div class="Bilgiler Bold" style="width: 40px;">Count</div>
			<div class="Bilgiler Bold KanalAdi">Video Name</div>
			<div class="Bilgiler Bold SonDiv">Video Source Links</div>
			<div class="Temizle"></div>
		</div>
							
<?php

		$ProfilBaglantilari = trim($_POST['TwitterProfileUrl']);
		$ProfilBaglantilari = explode("\n", $ProfilBaglantilari);
		$ProfilBaglantilari = array_map('trim', $ProfilBaglantilari);
		foreach ($ProfilBaglantilari as $Key => $Baglanti) {

			$KaynakUrl = '';
			$Sonuclar = GetVideoSourceUrl($Baglanti);
			$Baslik = $Sonuclar[0];
			$KaynakUrller = $Sonuclar[1];
			foreach ($KaynakUrller as $key => $Sonuc) {

				$KaynakUrl .= $Sonuc.', ';

			}

			$KaynakUrl = trim($KaynakUrl, ', ');

?>

			<div class="GenelBilgiler" <?=$Key%2?'':'style="background-color: #eff7ff;"'?>>
				<div class="Bilgiler" style="width: 40px;"><?=($Key+1)?></div>
				<div class="Bilgiler KanalAdi"><a href="<?=$Baglanti?>" target="_blank"><?=$Baslik?></a></div>
				<div class="Bilgiler SonDiv" style="padding: 0px 5px;"><?=$KaynakUrl?></div>
				<div class="Temizle"></div>
			</div>


<?php

		}

?>

	</div>

<?php

	}

?>
	<center><h2>Youtube Video Links</h2></center>
	<div class="Duzen">
		
		<form action="" method="post">
			<label style="margin-left: 10px; margin-top: 5px; font-weight: bold; color: #474747; float: left;">Youtube Video Links</label>
			<textarea placeholder="Examples:&#10;https://www.youtube.com/watch?v=vI4LHl4yFuo&#10;https://www.youtube.com/watch?v=Aia-B6_hrjo&#10;https://www.youtube.com/watch?v=3Hj3sFodt7E" name="TwitterProfileUrl" id="TwitterProfileUrl"></textarea>
			<input type="submit" value="Check" id="KontrolEt">
		</form>
		<div class="Temizle"></div>

	</div>

</body>
</html>