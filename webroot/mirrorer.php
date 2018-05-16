<?php
error_reporting(E_ALL ^ E_NOTICE | E_STRICT);

require('config/database.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');
require('lib/dirs.php');
require('lib/snippets.php');
sqlConnect();

function unparse_url($parsed_url) { 
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
    $pass     = ($user || $pass) ? "$pass@" : ''; 
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
    return "$scheme$user$pass$host$port$path$query$fragment"; 
} 

function getFilename($url)
{
    return substr($url, strrpos($url, '/')+1);
}
function getExtension($url)
{
    return substr($url, strrpos($url, '.')+1);
}

$botuserid = 2207;
$myhost = 'http://nsmbhd.net';

//======================================
//======================================


function createFile($raw, $name) {
    global $botuserid, $dataDir, $myhost;
    
    $id = Shake();
    $hash = md5($raw);

    Query("insert into {files} (id, name, hash, date, user) values ({0}, {1}, {2}, {3}, {4})",
        $id, $name, $hash, time(), $botuserid);

    $dir = $dataDir."uploads/".substr($hash, 0, 2).'/';
    @mkdir($dir);
    $fp = fopen($dir.$hash, 'w');
    fwrite($fp, $raw);
    fclose($fp);

    return "$myhost/file/$id/$name";
}

//======================================
//======================================

$domains = [
    '/board.dirbaio.net/' => [rewriteDomain, 'nsmbhd.net'],
    '/kuribo64.cjb.net/' => [rewriteDomain, 'kuribo64.net'],
    '/pixhost.org/' => [pixhost],
    '/.*/' => [download],
];

$download_ignore_domains = [
    'localhost',
    'imagesload.net',
    'www.imagesload.net',
    'nsmbhd.net',
    'img1.uploadscreenshot.com',
];

$download_extensions = [
    'png',
    'jpg',
    'gif',
    'zip',
    '7z',
    'gz',
    'tar',
    'tgz',
    'nmb',
    'nmt',
    'nml',
    'nmp'
];
$download_valid_mimes = [
    'image/png',
    'image/jpg',
    'image/jpeg',
    'image/gif',
    'application/zip',
];

function rewriteDomain($url, $arg) {
    $parse = parse_url($url);
    $parse['host'] = $arg;
    return unparse_url($parse);
}

function gimme($url) {
    echo("Downloading: $url ...");
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 100); //timeout in seconds
    $raw=curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);

    $len = strlen($raw);
    echo("Got HTTP $httpcode, $len bytes\n");
    return $raw;
}

function download($url, $arg) {
    global $download_extensions, $download_valid_mimes, $download_ignore_domains;
    if(!in_array(getExtension($url), $download_extensions))
        return $url;
    $parse = parse_url($url);
    if(in_array($parse['host'], $download_ignore_domains)) return $url;

    $raw = gimme($url);

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimetype = $finfo->buffer($raw);
    echo "Mime type: ".$mimetype ;

    if(in_array($mimetype, $download_valid_mimes)) {
        echo(" VALID!\n");
        $url = createFile($raw, getFilename($url));
    } else {
        echo(" NOT VALID!\n");
    }
    
    return $url;
}

function pixhost($url, $arg) {
    if(!preg_match('#/[^/]*/[^/]*$#', $url, $match))
        return $url;
    
    $z = gimme('https://pixhost.org/show'.$match[0]);
    if(!preg_match('#https://img\d+.pixhost.org/images'.$match[0].'#', $z, $match))
        return $url;

    return download($match[0], $arg);
}

// Link RegExp
$editcache = [];

function cb($match) {
    global $pid, $domains, $editcache;
    echo($myhost.'/post/'.$pid."/\t");
    $url = $match[0];
    $old_url= $match[0];
    echo("$url\n");
    $parse = parse_url($url);
    
    if($editcache[$url])
        $url = $editcache[$url];
    else {
        foreach ($domains as $key => $value) {
            if(preg_match($key, $parse['host'])) {
                $url = $value[0]($url, $value[1]);
                break;
            }
        }
        $editcache[$old_url] = $url;
    }

    
    if($url != $old_url) {
        echo("Rewritten: $url\n");
    }
    return $url;
}

$posts = Query("SELECT
        p.*,
        pt.text, pt.revision
    FROM
        {posts} p
        LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision
    ORDER BY date ASC");

while($post = fetch($posts)) {
    $pid = $post['id'];

    $new_text = $post['text'];
    $new_text = preg_replace_callback('#https?://[^\[\]\'"> \n]*#', cb, $new_text);
    $new_text = preg_replace_callback('#(?<=\[img\])https?://[^\[\]\'">\n]*#', cb, $new_text);
    $new_text = preg_replace_callback('#(?<=\[url\])https?://[^\[\]\'">\n]*#', cb, $new_text);
    $new_text = preg_replace_callback('#(?<=\[url=)https?://[^\[\]\'">\n]*#', cb, $new_text);

    if($new_text != $post['text']) {
        $now = time();
        $rev = fetchResult("select max(revision) from {posts_text} where pid={0}", $pid);
        $rev++;
        $rPostsText = Query("insert into {posts_text} (pid,text,revision,user,date) values ({0}, {1}, {2}, {3}, {4})",
                            $pid, $new_text, $rev, $botuserid, $now);

        $rPosts = Query("update {posts} set currentrevision = currentrevision + 1 where id={0} limit 1",
                        $pid);

        print("POST EDITED -- see $myhost/post/$pid/\n");
    }
}

echo "Done!";

// TO DO
// - Ignore extension casing
// - NMT/NMB/NML... mime type detection
// - mediafire...?
// - pastebin/pastie
// - rewrite get.php links
// - rewrite old thread.php?id=X and /?page=thread&id=X
// - Stupid dropbox links like https://www.dropbox.com/s/1doy8car52pjmm6/logoSmall.png?dl=1
// - Why this not work http://nsmbhd.net/thread/3713-coulton-s-world-a-nsmb-rom-hack/
?>

