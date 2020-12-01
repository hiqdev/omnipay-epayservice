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

class CompletePurchaseResponseTest extends TestCase
{
    private $request;

    private $purse                  = 'ec12345';
    private $secret                 = '22SAD#-78G8sdf$88';
    private $hash                   = 'f974945d5549c760f8f3c9582da49d6e';
    private $description            = 'Test Transaction long description';
    private $transactionId          = '1SD672345A890sd';
    private $transactionReference   = 'sdfa1SD672345A8';
    private $amount                 = '1465.01';
    private $currency               = 'USD';
    private $testMode               = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'     => $this->purse,
            'secret'    => $this->secret,
            'testMode'  => $this->testMode,
        ]);
    }

    public function testInvalidHashException()
    {
        $this->setExpectedException('Omnipay\Common\Exception\InvalidResponseException', 'Invalid hash');
        new CompletePurchaseResponse($this->request, [
            'description'           => $this->description,
            'purse'                 => $this->purse,
            'secret'                => $this->secret,
            'testMode'              => $this->testMode,
        ]);
    }

    public function testSuccess()
    {
        $response = new CompletePurchaseResponse($this->request, [
            'testMode'              => $this->testMode,
            'check_key'             => $this->hash,
            'EPS_DESCRIPTION'       => $this->description,
            'EPS_GUID'              => $this->purse,
            'EPS_AMOUNT'            => $this->amount,
            'EPS_TRID'              => $this->transactionReference,
            'EPS_ACCNUM'            => 'TS1323',
            'EPS_CURRENCY'          => $this->currency,
            'EPS_RESULT'            => 'done',
            'MERCHANT_ORDER_ID'     => $this->transactionId,
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($this->transactionId,         $response->getTransactionId());
        $this->assertSame($this->transactionReference,  $response->getTransactionReference());
        $this->assertSame($this->amount,                $response->getAmount());
        $this->assertSame($this->hash,                  $response->getHash());
        $this->assertSame($this->currency,              $response->getCurrency());
    }
}
