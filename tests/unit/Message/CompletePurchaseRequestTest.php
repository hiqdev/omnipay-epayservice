<?php
/**
 * ePayService driver for the Omnipay PHP payment processing library.
 *
 * @link      https://github.com/hiqdev/omnipay-epayservice
 * @package   omnipay-epayservice
 * @license   MIT
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\ePayService\Message;

use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    private $purse                  = 'ec12345';
    private $secret                 = '22SAD#-78G8sdf$88';
    private $hash                   = '1d4d18e1eea386654e1af89e89f1a104'; // d41d8cd98f00b204e9800998ecf8427e 954f1176a05a5921118f49285beea2bb
    private $description            = 'Test Transaction long description';
    private $transactionId          = '1SD672345A890sd';
    private $transactionReference   = 'sdfa1SD672345A8';
    private $timestamp              = '1454331086';
    private $amount                 = '1465.01';
    private $currency               = 'USD';
    private $testMode               = true;

    public function setUp()
    {
        parent::setUp();

        $httpRequest = new HttpRequest([], [
            'check_key'         => $this->hash,
            'EPS_DESCRIPTION'   => $this->description,
            'EPS_GUID'          => $this->purse,
            'EPS_AMOUNT'        => $this->amount,
            'EPS_TRID'          => $this->transactionId,
            'EPS_ACCNUM'        => $this->transactionReference,
            'EPS_GUID'          => $this->purse,
        ]);

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->description,   $data['EPS_DESCRIPTION']);
        $this->assertSame($this->transactionId, $data['EPS_TRID']);
        $this->assertSame($this->hash,          $data['check_key']);
        $this->assertSame($this->amount,        $data['EPS_AMOUNT']);
        $this->assertSame($this->purse,         $data['EPS_GUID']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertInstanceOf('Omnipay\ePayService\Message\CompletePurchaseResponse', $response);
    }
}
