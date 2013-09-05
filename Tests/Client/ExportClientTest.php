<?php

namespace Wunderdata\MailchimpBundle\Tests\Client;

use Buzz\Browser;
use Buzz\Message\Response;
use Wunderdata\MailchimpBundle\Client\ExportClient;

class ExportClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $buzzBrowser;

    /**
     * @var ExportClient
     */
    private $client;

    public function setUp()
    {
        $this->buzzBrowser = $this->getMock('\Buzz\Browser');
        $this->client = new ExportClient('123456-us1', $this->buzzBrowser);
    }

    public function testFetchListWithInvalidStatus()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidStatusException');

        $this->client->fetchList('1234', 'invalid_status');
    }

    public function testFetchListWithInvalidDate()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidFormatException');

        $this->client->fetchList('1234', ExportClient::STATUS_SUBSCRIBED, 'test_segment', '2013-08-16');
    }

    public function testFetchListWithInvalidHashingAlgorithm()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidHashingAlgorithmException');

        $this->client->fetchList('1234', ExportClient::STATUS_SUBSCRIBED, 'test_segment', '2013-08-16 12:00:00', 'md5');
    }

    public function testFetchEcommerceOrdersWithInvalidDate()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidFormatException');

        $this->client->fetchEcommerceOrders('2012-08-13');
    }

    public function testFetchCampaignSubscriberActivityWithInvalidIncludeEmptyFlag()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidTypeException');

        $this->client->fetchCampaignSubscriberActivity('1234', 'crap');
    }

    public function testFetchCampaignSubscriberActivityWithInvalidDate()
    {
        $this->setExpectedException('\Wunderdata\MailchimpBundle\Exception\InvalidFormatException');

        $this->client->fetchCampaignSubscriberActivity('1234', false, '2013-09-03');
    }

    public function testFetchList()
    {
        $response = new Response();
        $response->setContent('["head1", "head2"]' . "\n" . '["body1","body2"]');
        $this->buzzBrowser->expects($this->once())->method('post')->will($this->returnValue($response));

        $result = $this->client->fetchList('1234', ExportClient::STATUS_SUBSCRIBED, 'testsegment', '2013-09-05 11:52:12', 'sha256');

        $this->assertCount(2, $result);
        $this->assertEquals(array('head1', 'head2'), $result[0]);
        $this->assertEquals(array('body1', 'body2'), $result[1]);
    }

    public function testFetchEcommerceOrders()
    {
        $response = new Response();
        $response->setContent('["head1", "head2"]' . "\n" . '["body1","body2"]');
        $this->buzzBrowser->expects($this->once())->method('post')->will($this->returnValue($response));

        $result = $this->client->fetchEcommerceOrders();

        $this->assertCount(2, $result);
        $this->assertEquals(array('head1', 'head2'), $result[0]);
        $this->assertEquals(array('body1', 'body2'), $result[1]);
    }

    public function testFetchCampaignSubscriberActivity()
    {
        $response = new Response();
        $response->setContent('["head1", "head2"]' . "\n" . '["body1","body2"]');
        $this->buzzBrowser->expects($this->once())->method('post')->will($this->returnValue($response));

        $result = $this->client->fetchCampaignSubscriberActivity('1234');

        $this->assertCount(2, $result);
        $this->assertEquals(array('head1', 'head2'), $result[0]);
        $this->assertEquals(array('body1', 'body2'), $result[1]);
    }
}