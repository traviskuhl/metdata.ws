<?php

namespace metadata\controller;
use \b;

b::route("*", function(){
    $url = b::api()->normalizeUrl(p('url'));
    return b::view()->setParams(array('url' => $url))->setFile(__DIR__."/../views/web");
});


b::route('test.html', function(){
    return file_get_contents(__DIR__."/../../tests/test.html");
});