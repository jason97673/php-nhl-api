<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Guzzle\Plugin\Mock\MockPlugin;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use IcyData\NHL\Client;
use IcyData\NHL\NHLObject\Team;

class TeamsTest extends TestCase {

    private $fixtures = [];

    private $client;

    public function setUp() {
        $this->guzzleClientMock = $this->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(array('get'))
            ->getMock();

        $this->client = new Client(new NullLogger());
        $this->client->setGuzzle($this->guzzleClientMock);

        $this->fixtures['list']        = file_get_contents(__DIR__ . '/../../../../fixtures/teams_list.json');
        $this->fixtures['list_expand'] = file_get_contents(__DIR__ . '/../../../../fixtures/teams_list_expand.json');
        $this->fixtures['get']         = file_get_contents(__DIR__ . '/../../../../fixtures/teams_get.json');
    }

    public function testList() {
        $this->guzzleClientMock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->stringContains('team'))
            ->willReturn(new GuzzleResponse(200, [], $this->fixtures['list']));

        $teams = $this->client->teams->list()->send();

        foreach ($teams as $team) {
            $this->assertTrue($team instanceof Team);
        }
    }

    /**
     * @expectedException \IcyData\NHL\Exception\NHLException
     */
    public function testListError() {
        $e = new ClientException("", new GuzzleRequest('GET', 'team'), new GuzzleResponse('404'));

        $this->guzzleClientMock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->stringContains('teams'))
            ->will($this->throwException($e));

        $this->client->teams->list()->send();
    }

    public function testGet() {
        $this->guzzleClientMock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->stringContains('teams/1'))
            ->willReturn(new GuzzleResponse(200, [], $this->fixtures['get']));

        $team = $this->client->teams->get(1)->send();
        $this->assertTrue($team instanceof Team);
    }

    public function testListWithExpand() {
        $this->guzzleClientMock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->stringContains('team'))
            ->willReturn(new GuzzleResponse(200, [], $this->fixtures['list_expand']));

        $teams = $this->client->teams->list()->withExpand('team.schedule.next')->send();

        foreach ($teams as $team) {
            $this->assertTrue($team instanceof Team);
        }
    }
}
