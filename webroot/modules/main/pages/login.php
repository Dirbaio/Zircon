<?php

function request()
{
    Url::setCanonicalUrl('/login');

    $breadcrumbs = array(
        array('url' => '/login', 'title' => 'Log in'),
    );

    renderPage('component.html', array(
        'component' => 'login',
        'breadcrumbs' => $breadcrumbs,
        'actionlinks' => array(),
        'title' => 'Log in',
    ));
}
