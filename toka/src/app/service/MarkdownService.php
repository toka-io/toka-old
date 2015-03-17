<?php
require_once('vendor/autoload.php');

class MarkdownService
{
    function __construct()
    {
    }
    
    /**
     * @note: Need to avoid XSS
     */
    function render($text) 
    {
        $ciconia = new \Ciconia\Ciconia();
        $html = $ciconia->render($text);
        
        return $html;
    }
}