<?php

class PodcatchIdentify
{
	public static identify($agent)
	{
		// Spider-Bots
		if(strpos($agent, 'Googlebot') !== false)
			return array('?', 'GoogleBot');
		
		return array('?', '?');
	}
}

?>