<?php

//convert numbers to currency format
if ( ! function_exists('currency_format'))
{
	function currency_format($number)
	{
		return 'Rp '.number_format($number, 2, "," , ".");
	}
}

?>