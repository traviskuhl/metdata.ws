<?php

namespace metadata\controller;
use \b;

b::route(
    array('api/page.{format}', 'api/page'),
    function($format='json', $url=false){

        // formats
        $formats = array(
            'json' => 'text/javascript',
            'xml' => 'application/xml'
        );

        // response
        b::response()->setAccept($formats[$format]);

        // check for url param
        if ($url == false AND p('url')) {
            $url = p('url');
        }

        // make sure we have a valid url
        $url = b::api()->normalizeUrl($url);

        // no sure
        if (!$url) {
            b::response()->setStatus(400);
            return array(
                'error' => 'unable to parse url'
            );
        }

        // resp
        if (($text = b::api()->fetch($url)) == false) {
            b::response()->setStatus(500);
            return array(
                'error' => 'unable to fetch url',
                'url' => $url
            );
        }

        $resp = b::api()->parseFromDom($text, $url);

        // url
        $resp['url'] = $url;

        // xml
        if ($format == 'xml') {

            // add item names to images
            foreach ($resp['images'] as $i => $img) {
                $resp['images'][$i]['_item'] = 'image';
            }

            // title and desc
            if (isset($resp['title'])) {
                $resp['*title'] = $resp['title'];
                unset($resp['title']);
            }
            if (isset($resp['description'])) {
                $resp['*description'] = $resp['description'];
                unset($resp['description']);
            }
            if (isset($resp['twitter']['description'])) {
                $resp['twitter']['*description'] = $resp['twitter']['description'];
                unset($resp['twitter']['description']);
            }
            if (isset($resp['twitter']['title'])) {
                $resp['twitter']['*title'] = $resp['twitter']['title'];
                unset($resp['twitter']['title']);
            }
            if (isset($resp['og']['description'])) {
                $resp['og']['*description'] = $resp['og']['description'];
                unset($resp['og']['description']);
            }
            if (isset($resp['og']['title'])) {
                $resp['og']['*title'] = $resp['og']['title'];
                unset($resp['og']['title']);
            }

            // place in wrapper
            $resp = array("response" => $resp);
        }


        // return a response
        return $resp;

    }
)
->validate('format', 'json|xml');
