<?php

function request()
{
	Url::setCanonicalUrl('/login');

	$breadcrumbs = array(
		array('url' => '/login', 'title' => __('Log in')),
	);

	renderPage('login.html', array(
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => array(),
		'title' => __('Log in'),
	));
}