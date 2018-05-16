<?php

function request($id)
{
    $fid = $id;
    $forum = Fetch::forum($fid);

    Permissions::assertCanViewForum($forum);
    Permissions::assertCanCreateThread($forum);

    Url::setCanonicalUrl('/#-:/new', $forum['id'], $forum['title']);

    // Retrieve the draft.
    $draft = Fetch::draft(1, $fid);

    $breadcrumbs = array(
        array('url' => Url::format('/#-:', $forum['id'], $forum['title']), 'title' => $forum['title']),
        array('url' => Url::format('/#-:/new', $forum['id'], $forum['title']), 'title' => __('New thread'), 'weak' => true)
    );

    $actionlinks = array(
    );

    renderPage('component.html', array(
        'component' => 'threadnew',
        'props' => array(
            'draftType' => 1,
            'draftTarget' => $fid,
            'draft' => $draft,
        ),

        'forum' => $forum,

        'breadcrumbs' => $breadcrumbs,
        'actionlinks' => $actionlinks,
        'title' => 'New thread',
    ));
}
