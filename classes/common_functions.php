<?php
// This is common functions's class created by Waseem Safder
class common_functions
{
	var $nullVal = NULL;
	
	function post($name)
	{
		if (array_key_exists($name, $_POST))
		{
			if (is_array($_POST[$name]))
				return $_POST[$name];
			else
				return trim($_POST[$name]);
		}
		else
			return $this->nullVal;
	}
	
	function get($name)
	{
		if (array_key_exists($name, $_GET))
		{
			if (is_array($_GET[$name]))
				return $_GET[$name];
			else
				return trim($_GET[$name]);
		}
		else
			return $this->nullVal;
	}
	
	function redirect($url)
	{
		header('location: '.$url);
		exit;
	}
	
	
	function printArray($array)
	{
		
		print '<pre><div style="text-algin:left;">';
		print_r($array);
		print '</div></pre>';
	}
	

	function getFullUrl()
	{
		/*** check for https ***/
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
		/*** return the full address ***/
		return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

        function isTimeSet($time){
            $strTime = '0000-00-00 00:00:00';
            if(trim($time) != $strTime){
                return true;
            } else {
                return false;
            }
        }

        function aasort (&$array, $key) {
            $sorter=array();
            $ret=array();
            reset($array);
            foreach ($array as $ii => $va) {
                $sorter[$ii]=$va[$key];
            }
            asort($sorter);
            foreach ($sorter as $ii => $va) {
                $ret[$ii]=$array[$ii];
            }
            $array=$ret;
        }
		
		
		
}
?>