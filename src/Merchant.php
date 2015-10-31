<?php

/*
 * ePayService plugin for PHP merchant library
 *
 * @link      https://github.com/hiqdev/php-merchant-epayservice
 * @package   php-merchant-epayservice
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\merchant\epayservice;

class Merchant extends \hiqdev\php\merchant\Merchant
{
    protected static $_defaults = [
        'system'      => 'epayservice',
        'label'       => 'ePayService',
        'actionUrl'   => 'https://online.epayservices.com/merchant/index.php',
        'confirmText' => 'OK',
    ];

    public function getDescription()
    {
        return strtr(parent::getDescription(), '_@', '-:');
    }

    public function getInputs()
    {
        return [
            'EPS_DESCRIPTION' => $this->description,
            'EPS_GUID'        => $this->purse,
            'EPS_AMOUNT'      => $this->sum,
            'client'          => $this->username,
            'EPS_RESULT_URL'  => $this->confirmUrl,
            'EPS_SUCCESS_URL' => $this->successUrl,
            'EPS_FAIL_URL'    => $this->failureUrl,
        ];
    }

    public function validateConfirmation($data)
    {
        if ($data['EPS_RESULT'] === 'fail') {
            return 'Failed at paysystem';
        }
        $str = $data['EPS_AMOUNT'] . $this->purse . $this->_secret;
        if (md5($str) !== strtolower($data['check_key'])) {
            return 'Wrong hash';
        }
        if ($data['EPS_RESULT'] !== 'done') {
            die(json_encode('OK')); ### THIS IS THE RETURN FOR SYSTEM
        }
        $this->mset([
            'from' => $data['EPS_ACCNUM'],
            'txn'  => $data['EPS_TRID'],
            'sum'  => $data['EPS_AMOUNT'],
            'time' => date('c'),
        ]);

        return;
    }
}
