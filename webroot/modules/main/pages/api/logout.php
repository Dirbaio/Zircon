<?php

function request()
{
    Session::end();
    jsonRedirect(Url::format('/'));
}

