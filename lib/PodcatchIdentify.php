<?php

class PodcatchIdentify
{
	private static $os = array(
		'iOS' => array(
			'CFNetwork',
			'AppleCoreMedia',
			'iPad',
			'iPhone',
		),
		
		'Android' => array(
			'GRJ90',
			'Android',
			'HTC Sensation',
			'HTC_Sensation',
			'HTC Desire',
			'HTC_Desire',
			'Dalvik',
			'GINGERBREAD',
		),
		
		'Symbian',
		'MeeGo',
		
		'OSX' => array(
			'Mac OS',
			'Macintosh',
		),
		
		'Windows' => array(
			'MSIE',
			'Trident',
			'Windows',
		),
		
		'Linux/Unix' => array(
			'Ubuntu',
			'Linux',
			'libsoup',
			'gvfs',
			'Lavf',
		),
	);
	
	
	private static $app = array(
		'Instacast',
		'GStreamer',
		'Google Listen' => array(
			'Google-Listen',
			'Google Listen',
		),
		'Downcast',
		'HTC Streaming Player' => 'HTC Streaming',
		'DoggCatcher',
		'BeyondPod',
		'Pocket Casts',
		'Stagefright',
		'Android Download Manager' => 'AndroidDownloadManager',
		'Banshee',
		'MPlayer',
		'Podkicker',
		'wget',
		'curl',
		'gPodder',
		'hPodder',
		'Winamp',
		'Blubrry PowerPress',
		'Free Download Manager' => 'FDM',
		'newsbeuter',
		'iCatcher',
		'Feedreader',
		'Minimal Reader' => 'com.jv.minimalreader',
		'gReader' => 'com.noinnion',
		'Logitech Media Server',
		'xine',
		
		'Miro' => array(
			'Miro',
			'getmiro.com',
		),
		'iTunes' => array(
			'iTunes',
			'iTMS',
		),
		'vlc' => 'vlc',
		'Windows Media Player' => array(
			'WMP',
			'NSPlayer',
			'WMFSDK',
		),
		
		'Apple Mail' => 'Mail',
		'Chrome' => array(
			'Chrome',
			'Chromium',
		),
		
		'Safari' => 'Safari',
		'Firefox' => array(
			'Firefox',
			'Iceweasel',
			'SeaMonkey',
		),
		'Opera' => array(
			'Opera',
			'Presto',
		),
		
		'Searchengine' => array(
			'Googlebot',
			'Baiduspider',
			'YandexBot',
			'Baiduspider',
			'MLBot',
			'CareerBot',
			'DoCoMo',
			'Xaldon',
			'WebSpider',
			'Yahoo! Slurp',
		),
	);
	
	public static function identify($agent)
	{
		return array(
			PodcatchIdentify::_identify($agent, PodcatchIdentify::$os),
			PodcatchIdentify::_identify($agent, PodcatchIdentify::$app),
		);
	}
	
	private static function _identify($agent, $data)
	{
		foreach($data as $label => $identification)
		{
			if(is_int($label))
				$label = $identification;
			
			if(is_string($identification) && stripos($agent, $identification) !== false)
			{
				return $label;
			}
			else if(is_array($identification))
			{
				foreach($identification as $singleIdentification)
				{
					if(stripos($agent, $singleIdentification) !== false)
						return $label;
				}
			}
		}
		
		return '-';
	}
}

?>