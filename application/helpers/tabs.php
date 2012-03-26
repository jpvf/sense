<?php

function tab_selected($class = 'selected active', $only_class_name = FALSE)
{
    if ($only_class_name !== FALSE)
    {
        echo $class;
        return;
    }
    echo " class='$class' ";
}