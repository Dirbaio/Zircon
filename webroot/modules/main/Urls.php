<?php

$urls = array(
    # Main board stuff
    'GET /' => 'board',
    'GET /board' => 'board',
    'GET /board.php' => 'board',
    'GET /#id(-:)?(/p#from)?' => 'forum',
    'GET /forum/#id(-:)?' => 'forum',
    'GET /#(-:)?/#id(-:)?(/p#from)?' => 'thread',
    'GET /thread/#id(-:)?' => 'thread',
    'GET /thread.php' => 'thread',
    'GET /post/#pid' => 'post',
    'GET /post.php' => 'post',
    'POST /api/markasread' => 'api/markasread',

    # Posting stuff
    'POST /api/threadreply' => 'api/threadreply',
    'GET /#id(-:)?/new' => 'threadnew',
    'POST /api/threadnew' => 'api/threadnew',
    'GET /post/#pid/edit' => 'postedit',
    'POST /api/postedit' => 'api/postedit',
    'POST /api/preview' => 'api/preview',
    'POST /api/savedraft' => 'api/savedraft',
    'POST /api/getquote' => 'api/getquote',
    'POST /api/threadpollvote' => 'api/threadpollvote',

    # Moderation stuff
    'POST /api/postdelete' => 'api/postdelete',
    'POST /api/threadrename' => 'api/threadrename',
    'POST /api/threadopen' => 'api/threadopen',
    'POST /api/threadclose' => 'api/threadclose',
    'POST /api/threadstick' => 'api/threadstick',
    'POST /api/threadunstick' => 'api/threadunstick',

    # Member stuff
    'GET /members' => 'members',
    'GET /u/#id(-:)?(/p#from)?' => 'member',
    'GET /profile/#id(-:)?' => 'member',
    'GET /profile.php' => 'member',
    'GET /u/#id(-:)?/edit' => 'memberedit',
    'POST /api/memberedit' => 'api/memberedit',
    'GET /u/#id(-:)?/posts(/p#from)?' => 'memberposts',
    'GET /u/#id(-:)?/threads(/p#from)?' => 'memberthreads',
    'POST /api/membercomment' => 'api/membercomment',

    # PM stuff
    'GET /u/#(-:)?/messages/new' => 'messagenew',
    'GET /message/#pid' => 'message',
    'GET /u/#uid(-:)?/messages/#tid(-:)?(/p#from)?' => 'messagethread',
    'GET /u/#id(-:)?/messages(/p#from)?' => 'messages',
    'POST /api/messagenew' => 'api/messagenew',
    'POST /api/messagereply' => 'api/messagereply',
    'POST /api/messagequote' => 'api/messagequote',

    # Misc stuff
    'GET /lastposts' => 'lastposts',

    # Session stuff
    'GET /login' => 'login',
    'POST /api/login' => 'api/login',
    'GET /register' => 'register',
    'POST /api/register' => 'api/register',
    'POST /api/logout' => 'api/logout',

    # Admin stuff
    'GET /recalc' => 'recalc',
    'GET /migrate_uploader' => 'migrate_uploader',

    # File stuff
    'GET /file/:id' => 'file',
    'GET /file/:id/$' => 'file',
    'GET /get.php' => 'file',

    'GET /upload' => 'upload',
    'GET /uploader' => 'upload',
    'GET /uploader.php' => 'upload',
    'POST /api/upload' => 'api/upload',

);
