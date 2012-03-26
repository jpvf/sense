<?php 


function _uid($table = NULL)
{
    $number = _random_id();
    
    $results = db::getInstance()->where("uid = $number")->get($table);

    if ($results->num_rows() > 0)
    {
        return _uid($table);
    }

    return $number;
}

function _random_id()
{
    $number = '';
    for ($i=0; $i<8; $i++) 
    { 
        $number .= rand(1,9); 
        
    }
    return $number;
}