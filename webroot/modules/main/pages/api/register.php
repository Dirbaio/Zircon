<?php

function request($username='', $pass='', $pass2='', $email='')
{
    Validate::notEmpty($username, "Please enter your username.");
    Validate::notEmpty($pass, "Please enter your password.");

	$user = Sql::querySingle("SELECT * FROM users WHERE name=?", $username);
	if($user)
		fail('Username is already taken.');
	if($pass != $pass2)
		fail('Passwords do not match.');

	$salt = Config::get('salt');
	$pss = Util::randomString();
	$hash = Util::hash($pass.$salt.$pss);

	Sql::query('INSERT INTO users (name, email, password, pss, regdate, lastactivity, lastip) VALUES (?,?,?,?,?,?,?)', 
		$username, $email, $hash, $pss, time(), time(), $_SERVER['REMOTE_ADDR']);

	$uid = Sql::insertId();
	Session::start($uid);

    json(Url::format('/'));
}