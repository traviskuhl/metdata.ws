<?php

namespace metadata\lib;
use \b;

b::plug('api', '\metadata\lib\api');

class api extends \bolt\plugin\singleton {

    public function fetch($url) {

        $resp = b::webservice(array(
                'headers' => array(
                    'User-Agenet' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.22 (KHTML, like Gecko) metadata.ws/0.1'
                )
            ))->request($url);

        if ($resp->code() == 200){
            return $resp->text();
        }

        return false;

    }

    public function parseFromDom($text, $url) {

        // base
        $base = str_replace(basename($url), '', $url);

        $dom = new \DOMDocument();
        $dom->strictErrorChecking = false;
        $dom->validateOnParse = false;
        $dom->recover = true;
        $dom->formatOutput = false;
        $dom->encoding = 'UTF-8';
        @$dom->loadHTML($text);

        $tags = array();

        // meta tags
        $head = $dom->documentElement->getElementsByTagName('head')->item(0);
        $metaTags = $head->getElementsByTagName('meta');
        $linkTags = $head->getElementsByTagName('link');
        $images = $dom->documentElement->getElementsByTagName('img');

            // find each meta tag
            foreach ($metaTags as $el) {
                $tags[] = array(
                    'tag' => 'meta',
                    'name' => $el->getAttribute('name'),
                    'property' => $el->getAttribute('property'),
                    'content' => $el->getAttribute('content')
                );
            }

            foreach ($linkTags as $el) {
                if (in_array($el->getAttribute('type'), array('text/css'))) { continue; }
                $tags[] = array(
                    'tag' => 'link',
                    'rel' => $el->getAttribute('rel'),
                    'type' => $el->getAttribute('type'),
                    'title' => $el->getAttribute('title'),
                    'href' => $el->getAttribute('href')
                );
            }

            foreach ($images as $el) {
                $tags[] = array(
                    'tag' => 'img',
                    'src' => $el->getAttribute('src'),
                    'width' => $el->getAttribute('width'),
                    'height' => $el->getAttribute('height'),
                    'alt' => $el->getAttribute('alt'),
                    'title' => $el->getAttribute('title'),
                );
            }

            // title
            $tags[] = array(
                'tag' => 'title',
                'value' => $head->getElementsByTagName('title')->item(0)->nodeValue
            );

        // loop through meta tags and pull
        // out og and twitter
        $resp = array(
            'title' => false,
            'description' => false,
            'keywords' => false,
            'icon' => false,
            'logo' => false,
            'rss' => false,
            'search' => false,
            'canonical' => false,
            'og' => array(),
            'twitter' => array(),
            'oembed' => array(),
            'images' => array(),
        );

        // loop through meta tags
        foreach ($tags as $i => $item) {
            switch($item['tag']) {

                // meta tag
                case 'meta':
                    $name = p('name', false, $item);
                    $prop = p('property', false, $item);
                    $np = ($name ?: $prop);
                    if ($np) {
                        if (substr($np,0,2) == 'og') {
                            $_ = substr($np,3);
                            if (strpos($_, ':') !== false) {
                                $parts = explode(':', $_);
                                if (!array_key_exists($parts[0], $resp['og'])) { $resp['og'][$parts[0]] = array(); }
                                $resp['og'][$parts[0]][$parts[1]] = $item['content'];
                            }
                            else if (in_array($_, array('image','video','audio','article'))) {
                                if (!array_key_exists($_, $resp['og'])) { $resp['og'][$_] = array(); }
                                $resp['og'][$_]['url'] = $item['content'];
                            }
                            else {
                                $resp['og'][$_] = $item['content'];
                            }
                        }
                        else if (substr($np,0,7) == 'twitter') {
                            $_ = substr($np,8);
                            $resp['twitter'][$_] = $item['content'];
                        }
                        else if ($name == 'description') {
                            $resp['description'] = $item['content'];
                        }
                        else if ($name == 'keywords') {
                            $resp['keywords'] = $item['content'];
                        }
                    }
                    else {
                        unset($tags[$i]);
                    }

                break;

                // link tag
                case 'link':
                    $rel = p('rel', false, $item);
                    $type = p("type", false, $item);

                    if ($rel == 'shortcut icon') {
                        $resp['icon'] = $item['href'];
                    }
                    else if ($rel == 'canonical') {
                        $resp['canonical'] = $item['href'];
                    }
                    else if ($rel == 'alternate' AND $type == 'application/rss+xml') {
                        $resp['rss'] = $item['href'];
                    }
                    else if ($rel == 'search') {
                        $resp['search'] = $item['href'];
                    }
                    else if ($rel == 'alternate' AND $type == 'text/xml+oembed') {
                        $resp['oembed']['xml'] = $item['href'];
                    }
                    else if ($rel == 'alternate' AND $type == 'application/json+oembed') {
                        $resp['oembed']['json'] = $item['href'];
                    }
                break;

                // title
                case 'title':
                    $resp['title'] = $item['value'];
                break;

                // images
                case 'img':
                    unset($item['tag']);
                    $item['src'] = (substr($item['src'], 0, 4) === 'http' ? $item['src'] : rtrim($base,'/').'/'.ltrim($item['src'], '/'));
                    $resp['images'][] = $item;
                break;

            };
        }

        return $resp;

    }



}