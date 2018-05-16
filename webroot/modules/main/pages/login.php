<?php

function request()
{
    Url::setCanonicalUrl('/login');

    $breadcrumbs = array(
        array('url' => '/login', 'title' => 'Log in'),
    );

    renderPage('login.html', array(
        'breadcrumbs' => $breadcrumbs, 
        'actionlinks' => array(),
        'title' => 'Log in',
    ));
}