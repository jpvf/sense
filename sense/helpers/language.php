<?php 

function _lang($item)
{
    return Language::getInstance()->line($item);
}