<?php

namespace tests\functional\AppBundle\Page;


use Symfony\Component\DomCrawler\Crawler;
use tests\PageTestCase;
use tests\traits\DatabaseDictionary;
use WhereCanIWatch\Domain\Broadcast\Broadcast;
use WhereCanIWatch\Domain\Broadcast\TVChannel;

class BroadcastSearchPageTest extends PageTestCase
{
    use DatabaseDictionary;

    protected function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();
    }

    protected function createPage()
    {
        return new BroadcastSearchPage($this->client()->request('GET', '/search'));
    }

    /** @test */
    public function displaysFormWithNoResultsWhenOpened()
    {
        $this->assertSingleOccurrenceOf($this->page()->searchForm());
        $this->assertDoesNotExist($this->page()->searchResults());
    }

    /** @test */
    public function displaysResultsIfFound()
    {
        $broadcast = new Broadcast(
            'Rambo',
            TVChannel::named('TVP 1'),
            new \DateTime('+1 hour'),
            new \DateTime('+2 hour')
        );

        $this->save($broadcast);

        $crawler = $this->client()->submit($this->page()->searchFor('Rambo'));

        $this->page()->refresh($crawler);

        $searchResults = $this->page()->searchResults();

        $this->assertSingleOccurrenceOf($searchResults);
        $this->assertBroadcastExist($broadcast, $this->page()->searchResultAt(0));
    }

    private function assertBroadcastExist(Broadcast $broadcast, Crawler $resultRow)
    {
        $this->assertContains($broadcast->name(), $resultRow->text());
        $this->assertContains((string) $broadcast->tvChannel(), $resultRow->text());
    }
}