<?php

function validate_timezone($val) {
    $timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
    return in_array($val, $timezones);
}

function validate_email($val) {
    return $val === '' || filter_var($val, FILTER_VALIDATE_EMAIL);
}
function validate_bool(&$val) {
    if($val) $val = 1;
    else $val = 0;
    return true;
}
function validate_sex($val) {
    return $val === 0 || $val === 1 || $val === 2;
}

function request($id, $user, $password='')
{
    $userdata = $user;
    $user = Fetch::user($id);
    Permissions::assertCanEditUser($user);

    $salt = Config::get('salt');

    $fields = array(
        'powerlevel' => null,
        'minipic' => null,
        'picture' => null,
        'signature' => null,
        'bio' => null,
        'sex' => validate_sex,
        'realname' => null,
        'location' => null,
        'birthday' => null,
        'email' => validate_email,
        'homepageurl' => null,
        'homepagename' => null,
        'timezone' => validate_timezone,
        'theme' => null,
        'showemail' => validate_bool,
    );

    $features = Permissions::getProfileFeatures();
    if($features['title']) {
        $fields['title'] = null;
    }
    if($features['color']) {
        $fields['color'] = null;
        $fields['hascolor'] = validate_bool;
    }

    $sets = array();
    $args = array();
    foreach($fields as $field => $validator) {
        if($validator) {
            $verdict = $validator($userdata[$field]);
            if($verdict === false)
                fail('Invalid '.$field);
            if($verdict !== true)
                fail('Invalid '.$field.': '.$verdict);
        }
        $sets[] = $field.' = ?';
        $args[] = $userdata[$field];
    }

    if($userdata['pass'] || $userdata['pass2']) {
        if($userdata['pass'] != $userdata['pass2'])
            fail('Passwords do not match.');

        $pss = Util::randomString();
        $sets[] = 'password = ?';
        $args[] = Util::hash($userdata['pass'].$salt.$pss);
        $sets[] = 'pss = ?';
        $args[] = $pss;
    }

    // Check if modifications require password confirmation.
    if($userdata['pass'] || $userdata['email'] != $user['email'] || $userdata['powerlevel'] != $user['powerlevel']) {
        if(!$password)
            json('password_needed');

        $me = Session::get();
        if($me['password'] !== Util::hash($password.$salt.$me['pss']))
            fail('Wrong password');
    }

    $args[] = $user['id'];
    Sql::query('UPDATE users SET ' . implode(',', $sets) . ' WHERE id=?', ...$args);

    jsonRedirect(Url::format('/u/#-:', $user['id'], $user['name']));
}
