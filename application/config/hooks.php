<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Doctrine profiler for DEBUG 
// Disable in production
/*
$hook['post_controller_constructor'][] = array(
    'class'    => 'Doctrine_Profiler_Hooks',
    'function' => 'profiler_start',
    'filename' => 'doctrine_profiler_hooks.php',
    'filepath' => 'hooks',
    );

$hook['post_controller'][] = array(
    'class'    => 'Doctrine_Profiler_Hooks',
    'function' => 'profiler_end',
    'filename' => 'doctrine_profiler_hooks.php',
    'filepath' => 'hooks',
    );
*/
/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */