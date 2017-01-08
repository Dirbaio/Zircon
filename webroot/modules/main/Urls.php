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
    'POST /api/newreply' => 'api/newreply',
    'GET /#id(-:)?/newthread' => 'newthread',
    'POST /api/newthread' => 'api/newthread',
    'GET /post/#pid/edit' => 'editpost',
    'POST /api/editpost' => 'api/editpost',
    'POST /api/preview' => 'api/preview',
    'POST /api/savedraft' => 'api/savedraft',
    'POST /api/getquote' => 'api/getquote',
    'POST /api/pollvote' => 'api/pollvote',

    # Moderation stuff
    'POST /api/renamethread' => 'api/renamethread',
    'POST /api/closethread' => 'api/closethread',
    'POST /api/openthread' => 'api/openthread',
    'POST /api/deletepost' => 'api/deletepost',
    'POST /api/stickthread' => 'api/stickthread',
    'POST /api/unstickthread' => 'api/unstickthread',

    # Member stuff
    'GET /members' => 'members',
    'GET /u/#id(-:)?(/p#from)?' => 'member',
    'GET /profile/#id(-:)?' => 'member',
    'GET /profile.php' => 'member',
    'GET /u/#id(-:)?/edit' => 'memberedit',
    'GET /u/#id(-:)?/posts(/p#from)?' => 'memberposts',
    'GET /u/#id(-:)?/threads(/p#from)?' => 'memberthreads',
    'POST /api/usercomment' => 'api/usercomment',
    
    # PM stuff
    'GET /u/#(-:)?/pm/new' => 'newprivate',
    'GET /pm/post/#pid' => 'pmpost',
    'GET /u/#uid(-:)?/pm/#tid(-:)?(/p#from)?' => 'pmthread',
    'GET /u/#id(-:)?/pm?(/p#from)?' => 'pmthreads',
    'POST /api/newprivate' => 'api/newprivate',
    'POST /api/privatereply' => 'api/privatereply',

    # Misc stuff
    'GET /lastposts' => 'lastposts',
    
    # Session stuff
    'GET /login' => 'login',
    'GET /register' => 'register',
    'GET /logout' => 'logout',

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