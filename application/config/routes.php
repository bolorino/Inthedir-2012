<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

// Get group/view/normalized-name as company/normalized-name
$route['(\w{2})/company/(:any)'] = "group/view/$2";
$route['company/(:any)'] = "group/view/$1";

// Get spectacle/view/normalized-name as spectacle/normalized-name
$route['(\w{2})/spectacle/(:any)'] = "show/view/$2";
$route['spectacle/(:any)'] = "show/view/$1";

// Companies by category
$route['(\w{2})/companies/(:any)'] = 'companies/view/$2';
$route['companies/(:any)'] = "companies/view/$1";

// Spectacles by category
$route['(\w{2})/shows/(:any)/(:any)'] = 'shows/view/$2/$3';
$route['shows/(:any)/(:any)'] = "shows/view/$1/$2";
$route['(\w{2})/shows/(:any)'] = 'shows/view/$2';
$route['shows/(:any)'] = "shows/view/$1";

// Festivals by category
$route['(\w{2})/festivals/(:any)'] = 'festivals/view/$2';
$route['festivals/(:any)'] = "festivals/view/$1";

// Get festival/view/normalized-name as festival/normalized-name
$route['(\w{2})/festival/(:any)'] = "festival/view/$2";
$route['festival/(:any)'] = "festival/view/$1";

// Offers by location
// First, leave market contact, detail & request
$route['(\w{2})/market/contact/(:num)'] = 'market/contact/$2';
$route['market/contact/(:num)'] = "market/contact/$1";

$route['(\w{2})/market/detail/(:num)'] = 'market/detail/$2';
$route['market/detail/(:num)'] = "market/detail/$1";

$route['(\w{2})/market/request'] = 'market/request';
$route['market/request'] = "market/request";

$route['(\w{2})/market/(:any)'] = 'market/view/$2';
$route['market/(:any)'] = "market/view/$1";

// Pages controller
$route['(\w{2})/page/(:any)'] = 'page/view/$2';
$route['page/(:any)'] = "page/view/$1";

// Language selection. Route example: http://domain.tld/en/controller => http://domain.tld/controller
$route['(\w{2})/(.*)'] = '$2';
$route['(\w{2})'] = $route['default_controller'];


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */