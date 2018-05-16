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
    $draft['fid'] = $fid;


    $breadcrumbs = array(
        array('url' => Url::format('/#-:', $forum['id'], $forum['title']), 'title' => $forum['title']),
        array('url' => Url::format('/#-:/new', $forum['id'], $forum['title']), 'title' => __('New thread'), 'weak' => true)
    );

    $actionlinks = array(
    );

    renderPage('threadnew.html', array(
        'forum' => $forum, 
        'draft' => $draft,

        'breadcrumbs' => $breadcrumbs, 
        'actionlinks' => $actionlinks,
        'title' => 'New thread',
    ));
}

