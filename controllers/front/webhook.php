<?php

class RapydmneeWebhookModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        // Read raw input
        $payload = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!$payload) {
            http_response_code(400);
            exit('Invalid payload');
        }

        // Example fields (depends on your API)
        $cartId   = (int) $payload['cart_id'];
        $status   = $payload['status']; // success / failed
        $amount   = (float) $payload['amount'];

        $cart = new Cart($cartId);

        if (!Validate::isLoadedObject($cart)) {
            http_response_code(404);
            exit('Cart not found');
        }

        // SUCCESS
        if ($status === 'PAID') {

            if ($cart->orderExists()) {
                http_response_code(200);
                exit('Order already created');
            }

            $this->module->validateOrder(
                $cart->id,
                Configuration::get('PS_OS_PAYMENT'), // PAID
                $amount,
                $this->module->displayName,
                null,
                [],
                null,
                false,
                $cart->secure_key
            );

            http_response_code(200);
            exit('OK');
        } else  {
            http_response_code(200);
            exit('Payment '.$status);
        }
    }
}
