<?php

namespace Wunderdata\MailchimpBundle\Client;

use Buzz\Browser;
use Wunderdata\MailchimpBundle\Exception\InvalidFormatException;
use Wunderdata\MailchimpBundle\Exception\InvalidHashingAlgorithmException;
use Wunderdata\MailchimpBundle\Exception\InvalidStatusException;

class ExportClient
{
    const STATUS_SUBSCRIBED = 'subscribed';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';
    const STATUS_CLEANED = 'cleaned';

    private $url;
    private $apiKey;

    /**
     * @var Browser
     */
    private $buzzBrowser;

    public function __construct($apiKey, Browser $buzzBrowser)
    {
        list($keyPart, $datacenter) = explode('-', $apiKey);
        $this->url = sprintf('http://%s.api.mailchimp.com/export/1.0/', $datacenter);
        $this->apiKey = $apiKey;
        $this->buzzBrowser = $buzzBrowser;

    }

    /**
     * @param $id
     * @param string $status
     * @param string|null $segment
     * @param string|null $since Format: YYYY-MM-DD HH:MM:SS
     * @param string|false $hashed Provide hashing algo if you want hashing. Currently only SHA256 supported.
     */
    public function fetchList($id, $status = self::STATUS_SUBSCRIBED, $segment = null, $since = null, $hashed = false)
    {
        $url = $this->url . 'list/';

        $params = array('id' => $id);
        if ($status !== null) {
            if (!in_array($status, array(self::STATUS_CLEANED, self::STATUS_SUBSCRIBED, self::STATUS_UNSUBSCRIBED))) {
                throw new InvalidStatusException('The subscriber status "' . $status . '" is invalid.');
            }
            $params['status'] = $status;
        }

        if ($segment !== null) {
            $params['segment'] = $segment;
        }

        if ($since !== null) {
            if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $since)) {
                throw new InvalidFormatException('The provided date format is invalid.');
            }
            $params['since'] = $since;
        }

        if ($hashed !== false) {
            if ($hashed != 'sha256') {
                throw new InvalidHashingAlgorithmException(
                    'The hashing algorithm you provided is not compatible with Mailchimp'
                );
            }
            $params['hashed'] = $hashed;
        }

        $body = http_build_query($params);

        $response = $this->buzzBrowser->post($url, array(), $body);
        var_dump($response);
    }

    public function fetchEcommerceOrders()
    {

    }

    public function fetchCampaignSubscriberActivity()
    {

    }
}