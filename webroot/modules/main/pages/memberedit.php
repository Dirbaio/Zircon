<?php
function generate_timezone_list()
{
    $timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );

    $timezone_offsets = array();
    foreach( $timezones as $timezone ) {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

        $name = $timezone;
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);

        $timezone_list[] = array(
            'id' => $timezone,
            'name' => "(${pretty_offset}) $name",
        );
    }

    return $timezone_list;
}

function request($id)
{
    $user = Fetch::user($id);
    Permissions::assertCanEditUser($user);

    Url::setCanonicalUrl('/u/#-:/edit', $user['id'], $user['name']);

    $fields = array(
        'id',
        'name',
        'powerlevel',
        'minipic',
        'picture',
        'title',
        'signature',
        'bio',
        'sex',
        'realname',
        'location',
        'birthday',
        'email',
        'homepageurl',
        'homepagename',
        'timezone',
        'theme',
        'showemail',
        'color',
        'hascolor',
    );

    $themes = array(
        array('id' => 'cheese', 'name' => 'Old Cheese'),
        array('id' => 'abxd20', 'name' => 'ABXD 2.0'),
        array('id' => 'abxd30', 'name' => 'ABXD 3.0'),
        array('id' => 'trash', 'name' => 'TRaSH'),
    );

    $userdata = array();
    foreach ($fields as $field) {
        $userdata[$field] = $user[$field];
    }

    $timezones = generate_timezone_list();

    $breadcrumbs = array(
        array('url' => Url::format('/members'), 'title' => __("Members")),
        array('user' => $user),
        array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile'), 'weak' => true),
    );

    $actionlinks = array();

    renderPage('component.html', array(
        'component' => 'memberedit',
        'props' => array(
            'user' => $userdata,
            'timezones' => $timezones,
            'themes' => $themes,
            'features' => Permissions::getProfileFeatures(),
        ),
        'user' => $user,
        'breadcrumbs' => $breadcrumbs,
        'actionlinks' => $actionlinks,
        'title' => 'Edit profile',
    ));
}
