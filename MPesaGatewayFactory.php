<?php

namespace Payum\Mpesa;

use Action\RegisterUrlAction;
use Payum\Mpesa\Action\AuthorizeAction;
use Payum\Mpesa\Action\CancelAction;
use Payum\Mpesa\Action\ConvertPaymentAction;
use Payum\Mpesa\Action\CaptureAction;
use Payum\Mpesa\Action\NotifyAction;
use Payum\Mpesa\Action\RefundAction;
use Payum\Mpesa\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class MPesaGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'mpesa',
            'payum.factory_title' => 'Mpesa',

            'payum.action.register_url' => new RegisterUrlAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['consumer_key', 'consumer_secret'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array)$config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
