<?php

namespace tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class IntegrationTestCase extends WebTestCase
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @return ContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

    protected function setUp()
    {
        parent::setUp();

        $this->container = static::createClient()->getContainer();
    }
}
