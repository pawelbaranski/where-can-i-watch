<?php

namespace tests\integration\Infrastructure\Doctrine;


use tests\IntegrationTestCase;
use tests\traits\DatabaseDictionary;
use WhereCanIWatch\Domain\Broadcast\Broadcast;
use WhereCanIWatch\Domain\Broadcast\TVChannel;
use WhereCanIWatch\Infrastructure\Doctrine\ORMBroadcastRepository;

class ORMBroadcastRepositoryTest extends IntegrationTestCase
{
    use DatabaseDictionary;

    /** @var ORMBroadcastRepository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = $this->container()->get('app.broadcast_repository.orm');
        $this->purgeDatabase();
    }

    /** @test */
    public function findsNotFinishedBroadcastsOfGivenName()
    {
        $finishedBroadcast = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('yesterday'),
            new \DateTime('yesterday')
        );

        $notFinishedBroadcast = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('now'),
            new \DateTime('+1 hour')
        );

        $this->saveAll([$finishedBroadcast, $notFinishedBroadcast]);

        $found = $this->repository->findNotFinished('test', new \DateTime('-1 hour'));

        $this->assertEquals([$notFinishedBroadcast], $found);
    }

    /** @test */
    public function doesNotFindNotFinishedBroadcastIfNameDoesNotMatch()
    {
        $notFinishedBroadcast = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('now'),
            new \DateTime('+1 hour')
        );

        $this->save($notFinishedBroadcast);

        $this->assertSame([], $this->repository->findNotFinished('some', new \DateTime('-1 hour')));
    }

    /** @test */
    public function notFinishedBroadcastsAreOrderedByEndDate()
    {
        $notFinishedBroadcast1 = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('+1 hour'),
            new \DateTime('+2 hour')
        );

        $notFinishedBroadcast2 = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('now'),
            new \DateTime('+1 hour')
        );

        $notFinishedBroadcast3 = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('+3 hour'),
            new \DateTime('+4 hour')
        );

        $this->saveAll([$notFinishedBroadcast1, $notFinishedBroadcast2, $notFinishedBroadcast3]);

        $found = $this->repository->findNotFinished('test', new \DateTime());

        $this->assertEquals([
            $notFinishedBroadcast2,
            $notFinishedBroadcast1,
            $notFinishedBroadcast3
        ], $found);
    }

    /** @test */
    public function returnsEmptyArrayIfThereAreNoBroadcastsFound()
    {
        $this->assertSame([], $this->repository->findNotFinished('test', new \DateTime()));
    }

    /** @test */
    public function findsNotFinishedBroadcastsOrderedByTVChannelAndStartDate()
    {
        $notFinishedBroadcast1 = new Broadcast(
            'test',
            TVChannel::named('test-ch2'),
            new \DateTime('+1 hour'),
            new \DateTime('+2 hour')
        );

        $notFinishedBroadcast2 = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('+2 hour'),
            new \DateTime('+3 hour')
        );

        $notFinishedBroadcast3 = new Broadcast(
            'test',
            TVChannel::named('test-ch2'),
            new \DateTime('+2 hour'),
            new \DateTime('+3 hour')
        );

        $notFinishedBroadcast4 = new Broadcast(
            'test',
            TVChannel::named('test-ch1'),
            new \DateTime('+1 hour'),
            new \DateTime('+2 hour')
        );

        $this->saveAll([
            $notFinishedBroadcast1,
            $notFinishedBroadcast2,
            $notFinishedBroadcast3,
            $notFinishedBroadcast4
        ]);

        $found = $this->repository->findNotFinishedOrderedByTVChannelAndStartDate(new \DateTime('-1 hour'));

        $this->assertEquals([
            $notFinishedBroadcast4,
            $notFinishedBroadcast2,
            $notFinishedBroadcast1,
            $notFinishedBroadcast3,
        ], $found);
    }
}