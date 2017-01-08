<?php

function request()
{
	Url::setCanonicalUrl('/register');

	$breadcrumbs = array(
		array('url' => '/register', 'title' => __('Register')),
	);

	renderPage('register.html', array(
		'breadcrumbs' => $breadcrumbs,
		'actionlinks' => array(),
		'title' => __('Register'),
	));
}