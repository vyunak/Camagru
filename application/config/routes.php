<?php

return [

//	Main

	'' => [
		'controller' => 'main',
		'action' => 'index'
	],

	'about' => [
		'controller' => 'main',
		'action' => 'about'
	],

	'webcam' => [
		'controller' => 'main',
		'action' => 'webcam'
	],

//	Account

	'verify' => [
		'controller' => 'account',
		'action' => 'verify'
	],

	'settings' => [
		'controller' => 'account',
		'action' => 'settings'
	],

	'account/login' => [
		'controller' => 'account',
		'action' => 'login'
	],

	'account/logout' => [
		'controller' => 'account',
		'action' => 'logout'
	],

	'account/register' => [
		'controller' => 'account',
		'action' => 'register'
	],

	'account/password' => [
		'controller' => 'account',
		'action' => 'password'
	],

	'account/reset' => [
		'controller' => 'account',
		'action' => 'reset'
	],

//	Profile

	'profile' => [
		'controller' => 'profile',
		'action' => 'profile'
	],

	'(profile)/([0-9]*)' => [
		'controller' => 'profile',
		'action' => 'profileId'
	],

	'(photo)/([0-9]*)' => [
		'controller' => 'profile',
		'action' => 'photoId'
	],

	'post/new' => [
		'controller' => 'profile',
		'action' => 'newPost'
	],
	
	'post/delete' => [
		'controller' => 'profile',
		'action' => 'deletePost'
	],
	
	'post/comment/new' => [
		'controller' => 'profile',
		'action' => 'newComment'
	],
	
	'post/comment/delete' => [
		'controller' => 'profile',
		'action' => 'deleteComment'
	],
	
	'post/like' => [
		'controller' => 'profile',
		'action' => 'likePost'
	],

];

?>