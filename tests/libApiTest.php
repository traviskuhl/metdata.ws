<?php

class libApiTest extends MetaDataTest {

    public function setUp() {
        // $root = realpath(__DIR__."/../");
        // b::init(array(
        //     'load' => array(
        //         $root.'/lib/*.php',
        //     )
        // ));
    }

    public function testFetch() {
        $this->assertFalse(false);
    }

    public function testParseFromDom() {
        $text = file_get_contents(__DIR__."/test.html");
        $resp = b::api()->parseFromDom($text);

        // var_dump($resp); die;

    }


}