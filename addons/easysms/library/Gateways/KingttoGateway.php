<?php

/*
 * This file is part of the overtrue/easy-sms.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace addons\easysms\library\Gateways;

use addons\easysms\library\Contracts\MessageInterface;
use addons\easysms\library\Contracts\PhoneNumberInterface;
use addons\easysms\library\Exceptions\GatewayErrorException;
use addons\easysms\library\Support\Config;
use addons\easysms\library\Traits\HasHttpRequest;

/**
 * Class KingttoGateWay.
 *
 * @see http://www.kingtto.cn/
 */
class KingttoGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://101.201.41.194:9999/sms.aspx';

    const ENDPOINT_METHOD = 'send';

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface     $message
     * @param Config               $config
     *
     * @return \Psr\Http\Message\ResponseInterface|array|string
     *
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $params = [
            'action' => self::ENDPOINT_METHOD,
            'userid' => $config->get('userid'),
            'account' => $config->get('account'),
            'password' => $config->get('password'),
            'mobile' => $to->getNumber(),
            'content' => $message->getContent(),
        ];

        $result = $this->post(self::ENDPOINT_URL, $params);

        if ('Success' != $result['returnstatus']) {
            throw new GatewayErrorException($result['message'], $result['remainpoint'], $result);
        }

        return $result;
    }
}
