<?php

namespace metadata\controller;
use \b;

b::route(
    array('api/{format}/{url}', 'api/{url}'),
    function($format='json', $url=false){

        // make sure we have a valid url
        $url = filter_var((preg_match('#^https?://#', $url) ? $url : "http://".$url), FILTER_VALIDATE_URL);

        // no sure
        if (!$url) {
            die('bad');
        }

        // resp
        if (($text =  b::api()->fetch($url)) == false) {
            die('non 200');
        }

        $resp = b::api()->parseFromDom($text, $url);

        // tags
        if (p('tags')) {
            $resp['tags'] = $tags;
        }

        // xml
        if ($format == 'xml') {
            foreach ($resp['images'] as $i => $img) {
                $resp['images'][$i]['_item'] = 'image';
            }
            $resp = array("response" => $resp);
        }

        // formats
        $formats = array(
            'json' => 'text/javascript',
            'xml' => 'application/xml'
        );

        // biew
        $view = new \bolt\view();

        // set our content
        $view
            ->setData($resp)
            ->setAccept(array($formats[$format]))
            ->hasExecuted(true);

        return $view;

    }
)
->validate('format', '(json|xml)')
->validate('url', '.*');
