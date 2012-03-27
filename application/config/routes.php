<?php 

/*
|--------------------------------------------------------------------------
| Routes Configuration
|--------------------------------------------------------------------------
|
| It is possible to configure different kinds of routes, for example you 
| can call an URI like this:
|
| /about-us
|
| And route to...
|
| /content/index/aboutus
|
| the variable then will be like
|
| 'about-us' => 'content/index/aboutus'
|
| And it will work not only for fixed strings but you can also use regular
| expressions to make them work the way you like:
|
| /tasks/123123/index/1231231/
|
| And route to...
|
| /tasks/index/123123/123123/
|
| the variable then will be like
|
| 'tasks/(:num)/index/(:num)' => 'tasks/index/$1/$2'
|
| Or even more complex routes, your call!	
|
*/

return array(

	'default' => 'account/payments',

	'admin'   => 'account',

);