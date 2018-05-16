<?php 

function request($username='', $password='') {
    Validate::notEmpty($username, "Please enter your username.");
    Validate::notEmpty($password, "Please enter your password.");
    
    $salt = Config::get('salt');

    $user = Sql::querySingle("SELECT * FROM users WHERE name=?", $username);

    if(!$user || $user["password"] !== Util::hash($password.$salt.$user['pss']))
        fail("Wrong username or password");

    Session::start($user["id"]);
    json(Url::format('/'));
}