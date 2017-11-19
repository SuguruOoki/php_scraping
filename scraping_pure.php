<?php
$dom = new DOMDocument;
@$dom->loadHTMLFile('http://qiita.com/mpyw');
var_dump($dom);
$xpath = new DOMXPath($dom);
$entries = array_map(
    function ($node) use ($xpath) {
        return [
            'title'   => $xpath->evaluate('string(h1/a)', $node),
            'url'     => $xpath->evaluate('concat("http://qiita.com",h1/a/@href)', $node),
            'tags'    => array_map(
                function ($node) {
                    return $node->nodeValue;
                },
                iterator_to_array($xpath->query('.//li[@class="tag-label"]/a', $node))
            ),
            'date'    => $xpath->evaluate('string(.//li[@class="time"]/a)', $node),
            'stock'   => $xpath->evaluate('normalize-space(.//li[@class="stock"])', $node),
            'comment' => $xpath->evaluate('normalize-space(.//li[@class="comment"])', $node),
        ];
    },
    iterator_to_array($xpath->query('//div[@class="span7"]/section/article/div'))
);
var_dump($entries);
