<?php 

if ( ! function_exists('get_hour'))
{
	function get_hour($date = '')
	{
		return date('H:i:s a', strtotime($date));
	} 
}

if ( ! function_exists('sum_dates'))
{
	function sum_dates($date = '', $sum = 0, $by = 3600)
	{
		return date("Y-m-d H:i:s",(strtotime($date) + ($sum * $by)));
	}
}

if ( ! function_exists('date_plus_interval'))
{
	function date_plus_interval($date = '', $sum = 15 )
	{
	    return sum_dates($date, $sum, 60);
	}
}

if ( ! function_exists('now'))
{
	function now($hour = false)
	{
		return ( ! $hour ? date('Y-m-d') : date('Y-m-d H:i:s'));
	}
}

if ( ! function_exists('date_cmp'))
{		
	function date_cmp($date, $date1)
	{
		return strtotime($date) > strtotime($date1);
	}
}

if ( ! function_exists('strip_hour'))
{
	function strip_hour($date = '')
	{
		return date('Y-m-d', strtotime($date));
	}
}