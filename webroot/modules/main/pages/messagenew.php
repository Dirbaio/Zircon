<?php

function request()
{
    Permissions::assertCanDoStuff();
    Url::setCanonicalUrl('/u/#-:/messages/new', Session::id(), Session::get('name'));

    // Retrieve the draft.
    $draft = Fetch::draft(3, 0);

    $breadcrumbs = array(
        array('url' => Url::format('/members'), 'title' => __("Members")),
        array('user' => Session::get()),
        array('url' => Url::format('/u/#-:/messages', Session::id(), Session::get('name')), 'title' => __('Messages')),
        array('url' => Url::format('/u/#-:/messages/new', Session::id(), Session::get('name')), 'title' => __('New'), 'weak' => true),
    );

    $actionlinks = array(
    );

    renderPage('component.html', array(
        'component' => 'messagenew',
        'props' => array(
            'draftType' => 3,
            'draftTarget' => 0,
            'draft' => $draft,
        ),

        'breadcrumbs' => $breadcrumbs,
        'actionlinks' => $actionlinks,
        'title' => 'New PM',
    ));
}
