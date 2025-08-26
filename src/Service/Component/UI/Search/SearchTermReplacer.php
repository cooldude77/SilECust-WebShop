<?php

namespace Silecust\WebShop\Service\Component\UI\Search;

class SearchTermReplacer
{
    public function checkAndReplaceSearchTerm(string $url, string $searchTerm): string
    {
        $link = parse_url($url, PHP_URL_QUERY);
        parse_str($link, $output);

        if ($searchTerm != null)
            $output['searchTerm'] = $searchTerm;

        return substr($url, 0, strpos($url, '?') + 1) . http_build_query($output);


    }
}