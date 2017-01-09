<?php 

function request()
{
	Url::setCanonicalUrl('/members');

	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
	);

	$actionlinks = array(
	);

	renderPage('members.html', array(
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => 'Members',
	));
}