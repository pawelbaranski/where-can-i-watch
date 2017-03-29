<?php

namespace tests;

abstract class PageTestCase extends WebTestCase
{
    private $page;

    abstract protected function createPage();

    protected function setUp()
    {
        parent::setUp();

        $this->page = null;
    }

    protected function page()
    {
        if (!$this->page) {
            $this->page = $this->createPage();
        }

        return $this->page;
    }

    protected function assertSingleOccurrenceOf($node)
    {
        $this->assertCount(1, $node);
    }

    protected function assertDoesNotExist($node)
    {
        $this->assertCount(0, $node);
    }
}
