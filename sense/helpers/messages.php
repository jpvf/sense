<?php

class Message {

    private static $_instant = array();
    
    private static function _message($type = '', $text = '', $url = '')
    {
        session::getInstance()->set_flashdata(array(
            'text' => $text, 
            'type' => $type
        ));
        redirect($url); 
    }

    static function success($text = 'Success', $url = '')
    {
        return self::_message('success', $text, $url);
    }

    static function error($text = 'Error', $url = '')
    {
        return self::_message('error', $text, $url);
    }

    static function warning($text = 'Warning', $url = '')
    {
        return self::_message('warning', $text, $url);
    }

    static function info($text = 'Info', $url = '')
    {
        return self::_message('info', $text, $url);
    }

    static function instant($text = '', $type = 'error')
    {
        static::$_instant = array(
            'text' => $text, 
            'type' => $type
        );
    }


    static function get()
    {
        $session = session::getInstance();

        $text = ( ! empty(static::$_instant) ? static::$_instant['text'] : $session->get_flashdata('text'));
        $type = ( ! empty(static::$_instant) ? static::$_instant['type'] : $session->get_flashdata('type'));

        if (empty($text) OR empty($type))
        {
            return NULL;
        }
        
        $message = "<div class='alert alert-$type'>$text</div>";
        return $message;        
    }
}
