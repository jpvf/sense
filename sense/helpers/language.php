<?php 

function __($item)
{
    return Language::getInstance()->line($item);
}