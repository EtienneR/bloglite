<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'blog';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


# Admin
$route['admin']	= 'admin/users/signIn';

$route['users/signup']				= 'admin/users/signUp';

$route['admin/reset']				= 'admin/users/reset';
$route['admin/users']				= 'admin/users';
$route['admin/users/edit/(:any)']   = 'admin/users/edit/$1';
$route['admin/users/delete/(:any)'] = 'admin/users/delete/$1';
$route['admin/backup']				= 'admin/users/backup';
$route['admin/logout']				= 'admin/users/logout';

## Articles
$route['admin/articles']			   = 'admin/articles';
$route['admin/articles/edit']		   = 'admin/articles/edit';
$route['admin/articles/edit/(:any)']   = 'admin/articles/edit/$1';
$route['admin/articles/delete/(:num)'] = 'admin/articles/delete/$1';
$route['admin/articles/user/(:num)']   = 'admin/articles/user/$1';

## Pages
$route['admin/pages']			    = 'admin/pages';
$route['admin/pages/edit']		    = 'admin/pages/edit';
$route['admin/pages/edit/(:any)']   = 'admin/pages/edit/$1';
$route['admin/pages/delete/(:num)'] = 'admin/pages/delete/$1';

## Config
$route['admin/config'] = 'admin/config';
$route['admin/about']  = 'admin/config/about';

## API
$route['admin/api'] 				= 'admin/api';
$route['admin/api/articles'] 		= 'admin/api/fetchArticles';
$route['admin/api/articles/(:num)'] = 'admin/api/fetchOne/$1';


# Front
## Archives
$route['archives'] = $route['default_controller'] . '/archives';

## Contact
$route['contact'] = 'contact';

## Tags
$route['tag/(:any)']			 = $route['default_controller'] . '/tag/$1';
$route['tag/(:any)/page/(:num)'] = $route['default_controller'] . '/tag/$1/$2';

## Pagination
$route['page/(:num)'] = $route['default_controller'] . '/index/$1';

## Flux RSS
$route['feed'] = $route['default_controller'] . '/feed';

## Articles
$route['(:any)'] = $route['default_controller'] . '/article/$1';