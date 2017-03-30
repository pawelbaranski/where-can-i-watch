<?php

namespace tests\functional\AppBundle\Page;


use Symfony\Component\DomCrawler\Crawler;

class BroadcastSearchPage
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

    public function searchForm()
    {
        return $this->crawler->filter('form');
    }

    public function searchFor($query)
    {
        $form = $this->searchForm()->form();
        $form['broadcast_search'] = ['query' => $query];

        return $form;
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