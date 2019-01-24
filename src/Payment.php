<?php

namespace Geoff\EasyAllinpay;

use GuzzleHttp\Client;

class Payment
{
    const HTTP_TIMEOUT = 6.0;
    const SIGN_TYPE_RSA = 'RSA_1_256';

    public function jspay(array $data)
    {
        $uri = 'https://vsp.allinpay.com/apiweb/unitorder/pay';
        $data = array_merge($data, [
            'sub_appid' => config('easyallinpay.sub_appid'),
            'paytype' => config('easyallinpay.pay_type'),
            'cusip' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1',
        ]);
        return $this->_post($data, $uri);
    }

    public function refund(array $data)
    {
        $uri = 'https://vsp.allinpay.com/apiweb/unitorder/refund';
        $data = array_merge($data, ['op_user_id' => config('easyswiftpass.mch_id')]);
        return $this->_post($data, $uri);
    }

    public function query(array $data)
    {
        $uri = 'https://vsp.allinpay.com/apiweb/unitorder/query';
        return $this->_post($data, $uri);
    }

    public function cancel(array $data)
    {
        $uri = 'https://vsp.allinpay.com/apiweb/unitorder/cancel';
        return $this->_post($data, $uri);
    }

    public function isValidSign($sign, $data)
    {
        if ($data['sign_type'] === self::SIGN_TYPE_RSA) {
            return openssl_verify($this->_getRSASign($data), base64_decode($sign), config('easyswiftpass.rsa_public_key'), OPENSSL_ALGO_SHA256) === 1;
        } else {
            return $sign === $this->_getMD5Sign($data);
        }
    }

    private function _getNonceStr()
    {
        return md5(random_bytes(16));
    }

    private function _getSignStr($data)
    {
        if (is_array($data)) {
            ksort($data);
        }
        // sign不参与签名
        unset($data['sign']);
        $str = '';
        foreach ($data as $key => $value) {
            $str .= "{$key}={$value}&";
        }
        return $str;
    }

    private function _getMD5Sign($data)
    {
        $mch_key = config('easyallinpay.mch_key');
        $str = $this->_getSignStr($data) . "key=" . $mch_key;
        return strtoupper(md5($str));
    }

    private function _getRSASign($data)
    {
        $str = rtrim($this->_getSignStr($data), '&');
        openssl_sign($str, $signature, config('easyallinpay.rsa_private_key'), OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    private function _post(array $data, $uri)
    {
        $client = new Client([
            'timeout' => self::HTTP_TIMEOUT,
        ]);
        $response = $client->request('POST', $uri, ['form_params' => $this->_prepare($data)]);
        return $response->getBody()->getContents();
    }

    private function _prepare(array $data)
    {
        $data['cusid'] = config('easyallinpay.mch_id');
        $data['appid'] = config('easyallinpay.appid');
        $data['randomstr'] = $this->_getNonceStr();
        if (config('easyallinpay.sign_type') === self::SIGN_TYPE_RSA) {
            $data['sign_type'] = self::SIGN_TYPE_RSA;
            $data['sign'] = $this->_getRSASign($data);
        } else {
            $data['sign'] = $this->_getMD5Sign($data);
        }
        return $data;
    }
}