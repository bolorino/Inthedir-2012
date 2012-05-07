<?php
/*
 * Functions to format displayed messages (like warnings, errors, success)
 * 
 */

function itd_set_message($type, $text)
{
    $output = '<div id="message"> <div class="alert alert-'
    . $type
    . '">';
    
    if (is_array($text))
    {
        $output .= '<ul>';
        foreach ($text as $item)
        {
            $output .= '<li>' . $item . '</li>';
        }
        $output .= '</ul>';
    }
    else 
    {
        $output .= $text;
    }
    
    $output .= '</div></div>';
    
    return $output;
}
