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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * ePayService Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;

        if ($this->getHash() !== $this->calculateHash()) {
            throw new InvalidResponseException('Invalid hash');
        }
    }

    public function getHash()
    {
        return strtolower($this->data['check_key']);
    }

    public function calculateHash()
    {
        return md5($this->data['EPS_AMOUNT'] . $this->data['EPS_GUID'] . $this->request->getSecret());
    }

    public function isSuccessful()
    {
        return $this->data['EPS_RESULT'] === 'done';
    }

    public function getTransactionId()
    {
        return $this->data['EPS_TRID'];
    }

    public function getTransactionReference()
    {
        return $this->data['EPS_TRID'];
    }

    public function getAmount()
    {
        return $this->data['EPS_AMOUNT'];
    }

    public function getTestMode()
    {
        return (bool) $this->data['testMode'];
    }

    public function getCurrency()
    {
        return $this->data['EPS_CURRENCY'];
    }
}
