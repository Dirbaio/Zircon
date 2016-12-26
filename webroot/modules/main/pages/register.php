<?php
//page /register

function renderError($error, $username, $email) {
	$breadcrumbs = array(
		array('url' => '/register', 'title' => __('Register')),
	);

	renderPage('register.html', array(
		'username' => $username,
		'email' => $email,
		'error' => $error,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => array(),
		'title' => __('Register'),
	));
}

function request($username='', $pass='', $pass2='', $email='')
{
	Url::setCanonicalUrl('/register');

	if($username) {
		$user = Sql::querySingle("SELECT * FROM users WHERE name=?", $username);
		if($user)
			return renderError('Username is already taken.', $username, $email);
		if($pass != $pass2)
			return renderError('Passwords do not match.', $username, $email);
		if($pass == '')
			return renderError('Password cannot be empty.', $username, $email);

		$salt = Config::get('salt');
		$pss = Util::randomString();
		$hash = Util::hash($pass.$salt.$pss);

		Sql::query('INSERT INTO users (name, email, password, pss, regdate, lastactivity, lastip) VALUES (?,?,?,?,?,?,?)', 
			$username, $email, $hash, $pss, time(), time(), $_SERVER['REMOTE_ADDR']);

		$uid = Sql::insertId();
		Session::start($uid);
		Url::redirect('/');
	}

	$breadcrumbs = array(
		array('url' => '/register', 'title' => __('Register')),
	);

	renderPage('register.html', array(
		'username' => $username,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => array(),
		'title' => __('Register'),
	));
}