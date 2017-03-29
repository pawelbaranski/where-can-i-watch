<?php

namespace tests\functional\AppBundle\Page\Admin;


use Symfony\Component\DomCrawler\Crawler;
use tests\PageTestCase;
use tests\traits\DatabaseDictionary;
use tests\traits\FormLoginAuthDictionary;
use WhereCanIWatch\Domain\Broadcast\Broadcast;
use WhereCanIWatch\Domain\Broadcast\TVChannel;
use WhereCanIWatch\Domain\User\User;

class BroadcastListPageTest extends PageTestCase
{
    use FormLoginAuthDictionary;
    use DatabaseDictionary;

    protected function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();
    }

    protected function createPage()
    {
        return new BroadcastListPage($this->client()->request('GET', '/admin/broadcast/list'));
    }

    /** @test */
    public function displaysBroadcastsIfFound()
    {
        $this->userExists('admin', 'ROLE_ADMIN');
        $this->userIsAuthenticatedAs('admin');

        $broadcast = new Broadcast(
            'Rambo',
            TVChannel::named('TVP 1'),
            new \DateTime('+1 hour'),
            new \DateTime('+2 hour')
        );

        $this->save($broadcast);

        $searchResults = $this->page()->searchResults();

        $this->assertSingleOccurrenceOf($searchResults);
        $this->assertBroadcastExist($broadcast, $this->page()->searchResultAt(0));
    }

    /** @test */
    public function preventsAccessForNonAdminUser()
    {
        $this->userExists('user', 'ROLE_USER');
        $this->userIsAuthenticatedAs('user');

        $this->page();

        $this->assertStatusCodeEquals(403);
    }

    private function assertBroadcastExist(Broadcast $broadcast, Crawler $resultRow)
    {
        $this->assertContains($broadcast->name(), $resultRow->text());
        $this->assertContains((string) $broadcast->tvChannel(), $resultRow->text());
    }

    private function userExists($username, $role)
    {
        $userManager = $this->container()->get('fos_user.user_manager');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail("$username@example.com");
        $user->setRoles([$role]);
        $user->setPlainPassword('P@ssw0rd');
        $user->setEnabled(true);

        $userManager->updateUser($user);
    }
}