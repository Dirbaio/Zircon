<?php

function validate_timezone($val) {
    $timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
    return in_array($val, $timezones);
}

function request($id, $user)
{
    $userdata = $user;
    $user = Fetch::user($id);
    Permissions::assertCanEditUser($user);

    $fields = array(
        'powerlevel' => null,
        'minipic' => null,
        'picture' => null,
        'signature' => null,
        'bio' => null,
        'sex' => null,
        'realname' => null,
        'location' => null,
        'birthday' => null,
        'email' => null,
        'homepageurl' => null,
        'homepagename' => null,
        'timezone' => validate_timezone,
        'theme' => null,
        'showemail' => null,
    );

    $features = Permissions::getProfileFeatures();
    if($features['title']) {
        $fields['title'] = null;
    }
    if($features['color']) {
        $fields['color'] = null;
        $fields['hascolor'] = null;
    }

    $sets = array();
    $args = array();
    foreach($fields as $field => $validator) {
        if($validator && !$validator($userdata[$field]))
            fail('Invalid '.$field);
        $sets[] = $field.' = ?';
        $args[] = $userdata[$field];
    }

    $args[] = $user['id'];
    Sql::query('UPDATE users SET ' . implode(',', $sets) . ' WHERE id=?', ...$args);

    jsonRedirect(Url::format('/u/#-:', $user['id'], $user['name']));
}
