<?php

use Krystal\Stdlib\Dumper;

/**
 * Dumps a variable
 * 
 * @param mixed $variable
 * @param boolean $exit Whether to terminate script execution
 * @return void
 */
function d($var, $exit = true){
    Dumper::dump($var, $exit);
}
