<?php

namespace tests\traits;


use Symfony\Component\DomCrawler\Crawler;

trait FormLoginAuthDictionary
{
    protected function userIsAuthenticatedAs($username)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client()->request('GET', '/admin/login');

        $form = $crawler->selectButton('_submit')->form([
            '_username'  => $username,
            '_password'  => 'P@ssw0rd',
        ]);
        $this->client()->submit($form);
    }
}