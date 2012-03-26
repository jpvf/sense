<?php 

if( ! function_exists('gmd'))
{

    function gmd($date = '', $short = false, $sum = 0 )
    {
        $gmt = '-5'; 
        $actualDST = 0; 
        $gmt_diff = $gmt + $actualDST;
        
        if (!$short)
        {
        	 $city_time = strtotime($date)+($gmt_diff*3600)+($sum * 3600);
        	 $return = gmdate("Y-m-d h:i:s a",$city_time);
        }
        else
        {
        	 $city_time = strtotime($date)+($gmt_diff)+($sum * 3600);
        	 $return = gmdate("Y-m-d",$city_time);
        }
        
        return $return;
    }

}

function string_day_today()
{
    $day = date('w');
    
    $days = array(        
        'Domingo',
        'Lunes',
        'Martes',
        'Miercoles',
        'Jueves',
        'Viernes',
        'Sábado'
    );
    
    return $days[$day];
}


function get_hour($date = '')
{
	if (strpos($date, ' ') !== FALSE)
	{
		list($date, $hour) = explode(' ', $date);
		list($hour, $min, $sec) = explode(':', $hour);

		$time = 'am';

		if ($hour >= 13)
		{
			$time = 'pm';
			$hour = $hour - 12;
		}

		return $hour . ':' . $min . ':' . $sec . ' ' . $time;
	}

	return FALSE;
} 

function sum_dates($date = '', $sum = 0 )
{
	$city_time = strtotime($date)+($sum * 3600);
	$return = date("Y-m-d H:i:s",$city_time);
	return $return;
}
	
function date_plus_interval($date = '', $sum = 15 )
{
    $city_time = strtotime($date)+($sum * 60);
    $return = date("Y-m-d H:i:s",$city_time);
    return $return;
}

function get_date()
{
	$date = date('d-m-Y weekday');
	return $date;
}
	
function today($hour = FALSE)
{
	if ( ! $hour)
	{
		$date = date('Y-m-d');
	}
	else
	{
		$date = date('Y-m-d H:i:s');
	}
	
	return $date;
}
	
function business_to_calendar ($start_day_of_week, $business_days) {
	if ( $start_day_of_week == 0 ) {
		$additional_days = $additional_days + 1;
		$start_day_of_week = 1;
	}
	if ( $start_day_of_week == 6 ) {
		$additional_days = $additional_days + 2;
		$start_day_of_week = 1;
	}
	if ( $business_days + $start_day_of_week > 5 ) {
		$num_weekends = floor(($start_day_of_week+$business_days-1)/5);
		$additional_days = $additional_days + ($num_weekends * 2);
	}
	$calendar_days = $business_days + $additional_days;
	return $calendar_days;
	/* Funcion para actualizar la fecha de entrega, si es o no dia laboral es irrelevante, igual se actualiza
	$pre_orders = mysql_query ("SELECT turnaround FROM orders WHERE order_num=".$currord);
	$turn_calendar_days = business_to_calendar(date("w"), mysql_result($pre_orders,0,0));
	mysql_query ("UPDATE orders SET status=3, status_since=NOW(), date_placed=NOW(), deadline=DATE_ADD(NOW(), INTERVAL ".$turn_calendar_days." DAY) WHERE order_num=".$currord);*/
}
	
function cmp($a, $b)
{
    return strnatcmp($b, $a);
}
	
function date_cmp($date, $date1)
{
	if (strtotime($date) > strtotime($date1))
	{
		return TRUE;
	}	
	return FALSE;
}
	
function string_today()
{
    $date = today();
    	    
	list ($y, $m, $d) = explode('-', $date);
	
	$n = date('m', strtotime($date));

	$months = array(
	    'Ninguno',
	    'Enero',
    	'Febrero',
    	'Marzo',
    	'Abril',
        'Mayo',
    	'Junio',
    	'Julio',
    	'Agosto',
    	'Septiembre',
    	'Octubre',
    	'Noviembre',
    	'Diciembre'
	);
	
	if ($n < 10)
	{
        list($n, $m) = explode('0', $n);
	}
	else 
	{
	    $m = $n;
	}
	
    $date = $d.' de '.$months[$m].' de '.$y;   
	return $date; 
}
	
function date_to_string($date = '', $hora = TRUE)
{
    $lang = language::getInstance();
    
	if($date == '')
	{
		return;
	}
	
	if ($hora === FALSE OR strpos($date, ' ') === FALSE)
	{
	    $date = trim($date) . ' 00:00:00';
	}
	
	list($date, $hours) = explode(' ', $date);
	
	
	$fecha = gmd($date, true);
	list ($y, $m, $d) = explode('-', $fecha);
	$n = date('m', strtotime($fecha));

	$months = array(
	    'Ninguno',
	    'Enero',
    	'Febrero',
    	'Marzo',
    	'Abril',
        'Mayo',
    	'Junio',
    	'Julio',
    	'Agosto',
    	'Septiembre',
    	'Octubre',
    	'Noviembre',
    	'Diciembre'
	);
	
    if ($date == today())
    {
		$published = 'Hoy';
	}
	else
	{
	    list($n, $m) = explode('0', $n);
	    $published =  $months[$m] . ' ' . $d . ', ' . $y  ;   
	}
	return $published; 
}

function strip_hour($date = '')
{
   if ($date == '')
   {
       return;
   }
   
   $date = explode(' ', $date);
   return $date[0];
}