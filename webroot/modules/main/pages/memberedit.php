<?php 

function request($id)
{
    $user = Fetch::user($id);

    Url::setCanonicalUrl('/u/#-:/edit', $user['id'], $user['name']);


    $breadcrumbs = array(
        array('url' => Url::format('/members'), 'title' => __("Members")),
        array('user' => $user),
        array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile'), 'weak' => true),
    );

    $actionlinks = array();

    renderPage('memberedit.html', array(
        'user' => $user,
        'breadcrumbs' => $breadcrumbs, 
        'actionlinks' => $actionlinks,
        'title' => 'Edit profile',
    ));
}