<?php
/**
 * Sets the header description text
 *
 * @param string $text
 * @return string
 */
function set_header_description($text)
{
    $header_description = strip_tags($text);
    $header_description = str_replace("\"", '\'', $header_description);
    $header_description = substr($header_description,0 ,150).'...';

    return $header_description;
}