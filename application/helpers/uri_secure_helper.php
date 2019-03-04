<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL ^ E_DEPRECATED);

if (!function_exists('safe_b64encode')) {
	function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}
}

if (!function_exists('safe_b64decode')) { 
	function safe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
		$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
}

if (!function_exists('uri_encrypt')) { 
	function uri_encrypt($mprhase) {
		$key = "3x"; 
		$MASTERKEY = $key;
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $MASTERKEY, $iv);
		$return_value = safe_b64encode(mcrypt_generic($td, $mprhase));
		return trim($return_value);
	} 
}

if (!function_exists('uri_decrypt')) { 
	function uri_decrypt($mprhase) {
		$key = "3x"; 
		$MASTERKEY = $key;
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $MASTERKEY, $iv);
		$return_value = mdecrypt_generic($td, safe_b64decode($mprhase));
		return trim($return_value);
	} 
}

if (!function_exists('uri_segment')) { 
	function uri_segment($value) {
		$ci = & get_instance();
		return $ci->uri->segment($value);
	} 
}