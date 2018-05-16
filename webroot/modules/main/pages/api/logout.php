<?php

function request()
{
    Session::end();
    json(Url::format('/'));
}

