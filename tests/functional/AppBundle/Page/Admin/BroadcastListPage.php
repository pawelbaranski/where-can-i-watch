<?php

namespace tests\functional\AppBundle\Page\Admin;


use Symfony\Component\DomCrawler\Crawler;

class BroadcastListPage
{
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function refresh(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function searchResults()
    {
        return $this->crawler->filter('table');
    }

    public function searchResultAt($position)
    {
        return $this->searchResults()->filter('tbody > tr')->eq($position);
    }
}