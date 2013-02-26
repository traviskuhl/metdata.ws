<?php

namespace metadata\controller;
use \b;


class web extends \bolt\view {

    public function get($site=false) {

        var_dump($site); die;

    }

}


b::route('test.html', function(){
    return file_get_contents(__DIR__."/../../tests/test.html");
});