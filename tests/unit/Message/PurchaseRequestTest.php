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

class PurchaseRequestTest extends TestCase
{
    private $request;

    private $purse          = 'vip.vip@corporation.inc';
    private $secret         = '22SAD#-78G888';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $cancelUrl      = 'https://www.foodstore.com/failure';
    private $notifyUrl      = 'https://www.foodstore.com/notify';
    private $description    = 'Test Transaction long description';
    private $transactionId  = '12345ASD67890sd';
    private $amount         = '14.65';
    private $quantity       = '1';
    private $currency       = 'USD';
    private $testMode       = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'returnUrl'     => $this->returnUrl,
            'cancelUrl'     => $this->cancelUrl,
            'notifyUrl'     => $this->notifyUrl,
            'description'   => $this->description,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->purse,         $data['EPS_GUID']);
        $this->assertSame($this->returnUrl,     $data['EPS_SUCCESS_URL']);
        $this->assertSame($this->cancelUrl,     $data['EPS_FAIL_URL']);
        $this->assertSame($this->notifyUrl,     $data['EPS_RESULT_URL']);
        $this->assertSame($this->description,   $data['EPS_DESCRIPTION']);
        $this->assertSame($this->amount,        $data['EPS_AMOUNT']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertInstanceOf(\Omnipay\ePayService\Message\PurchaseResponse::class, $response);
    }
}
