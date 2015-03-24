<?php
//require_once('vendor/autoload.php');

class TokadownService
{
    function __construct()
    {
    }
    
    /**
     * @desc: Markup System
     * @author: Bob620
     * @note: Need to avoid XSS
     */
    function render($text) 
    {
        // Primary Stuff, split the string into lines
        $text = preg_split("/\n/", $text);
        $html = '';
        $type = 'normal';
        
        // Use each line and split it for sentences
        foreach ($text as &$sentence) {
            
            // Find if it's a listed line
            if (substr($sentence, 0, 1) == '-') {
                // If it's already a list, append to the list
                if ($type == 'list') {
                    $html = substr($html, 0, -5);
                } else {
                    // if it's a new list, make one
                    $type = 'list';
                    $html = $html.'<ul>';
                }
                // Make the line an item in the list and take out the delimiter
                $html = $html.'<li>';
                $sentence = substr($sentence, 1);
            } else {
                // If it's not a list make it's type 'normal'
                $type = 'normal';
            }
            
            // Split each sentence into words
            $sentenceSplit = explode(' ', $sentence);
            
            foreach ($sentenceSplit as &$word) {
                
                // Set up the 'All is complete, skip automated stuff'
                $stop = False;
                // Preset output
                $final = $word;
                
                // Find BOLD
                if (substr($word, 0, 2) == '**') {
                    if (strlen($word) > 2) {
                        if (substr($word, -2) == '**') {
                            $final = $this->renderBold($word);
                        }
                    }
                } else {
                    // Find ITALICIZE
                    $final = substr($word, 0);
                    if (substr($word, 0, 1) == '*') {
                        if (strlen($word) > 1) {
                            if (substr($word, -1) == '*') {
                                $final = $this->renderItalic($word);
                            }
                        }
                    }
                }
                
                // Find HEADER 3
                if (substr($word, 0, 3) == '###') {
                    if (strlen($word) > 3) {
                        $final = $this->renderHead3($sentence);
                        $stop = True;
                    }
                } else {
                    // Find HEADER 2
                    if (substr($word, 0, 2) == '##') {
                        if (strlen($word) > 2) {
                            $final = $this->renderHead2($sentence);
                            $stop = True;
                        }
                    } else {
                        // Find HEADER 1
                        if (substr($word, 0, 1) == '#') {
                            if (strlen($word) > 1) {
                                $final = $this->renderHead1($sentence);
                                $stop = True;
                            }
                        }
                    }
                }
                
                // Find Strikethrough
                if (substr($word, 0, 2) == '~~') {
                    if (strlen($word) > 2) {
                        if (substr($word, -2) == '~~') {
                            $final = $this->renderStrike($word);
                        }
                    }
                }
                
                // Find SuperScript
                foreach (explode(' ', $word) as &$letter) {
                    if ($letter == '^') {
                        if (strlen($word) > 1) {
                            $final = $this->renderSuper($word);
                        }
                    }
                }
                
                // Append words to a finalized sentence
                $html = $html.' '.$final;
                // Escape
                if ($stop) {
                    break;
                }
            }
            // Escape
            if ($stop) {
            } else {
                if ($type == 'list') {
                    $html = $html.'</li></ul>';
                } else {
                    // Append a line break
                    $html = $html.'<br />';
                }
            }
        }
        // Return Finalized Structure
        return $html;
    }
    // All renderers assume the word HAS it's delimiters intact
    // should fix that at some point...
    
    // BOLD
    function renderBold($word) 
    {
        return '<b>'.substr($word, 2, -2).'</b>';
    }
    
    // ITALIC
    function renderItalic($word) 
    {
        return '<em>'.substr($word, 1, -1).'</em>';
    }
    
    // HEADER 1
    function renderHead1($sentence) 
    {
        return '<h2>'.substr($sentence, 1).'</h2>';
    }
    
    // HEADER 2
    function renderHead2($sentence) 
    {
        return '<h3>'.substr($sentence, 2).'</h3>';
    }
    
    // HEADER 3
    function renderHead3($sentence) 
    {
        return '<h4>'.substr($sentence, 3).'</h4>';
    }
    
    // STRIKETHROUGH
    function renderStrike($word) 
    {
        return '<s>'.substr($word, 2, -2).'</s>';
    }
    
    // SUPERSCRIPT
    function renderSuper($word) 
    {
        return '<span style="vertical-align: super;">'.substr($word, 1).'</span>';
    }
}
