<?php

/*
 * ePayService driver for the Omnipay PHP payment processing library
 *
 * @link      https://github.com/hiqdev/omnipay-epayservice
 * @package   omnipay-epayservice
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\ePayService\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'purse',
            'amount', 'currency', 'description',
            'returnUrl', 'cancelUrl', 'notifyUrl'
        );

        return [
            'EPS_DESCRIPTION' => $this->getDescription(),
            'EPS_GUID'        => $this->getPurse(),
            'EPS_AMOUNT'      => $this->getAmount(),
            'EPS_RESULT_URL'  => $this->getNotifyUrl(),
            'EPS_SUCCESS_URL' => $this->getReturnUrl(),
            'EPS_FAIL_URL'    => $this->getCancelUrl(),
        ];
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
