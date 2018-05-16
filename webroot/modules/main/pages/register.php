<?php

function request()
{
    Url::setCanonicalUrl('/register');

    $breadcrumbs = array(
        array('url' => '/register', 'title' => __('Register')),
    );

    renderPage('component.html', array(
        'component' => 'register',
        'breadcrumbs' => $breadcrumbs,
        'actionlinks' => array(),
        'title' => __('Register'),
    ));
}
