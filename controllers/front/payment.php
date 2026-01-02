<?php

class RapydmneePaymentModuleFrontController extends ModuleFrontController
{
    private $apiBaseUrl = 'http://172.31.48.1:8085';

    public function init() {
        parent::init();
    }

    public function display() {
        return null;
    }

    public function postProcess()
    {
        $cart = $this->context->cart;

        if (!$cart || !$cart->id) {
            Tools::redirect('index.php');
        }

        // 1️⃣ Build payload
        $payload = $this->buildCheckoutPayload($cart);

        // 2️⃣ Call API
        $response = $this->createCheckoutSession($payload);

        // 3️⃣ Handle errors
        if (empty($response)) {
            $this->showJsError('API returned empty response');
            return;
        }

        if (!isset($response['checkoutSession'])) {
            $this->showJsError('checkoutSession missing in API response', $response);
            return;
        }

        if (empty($response['checkoutSession']['hostedPage'])) {
            $this->showJsError('hostedPage missing in API response', $response);
            return;
        }

        // 4️⃣ Redirect to hostedPage
        Tools::redirect($response['checkoutSession']['hostedPage']);
    }

    private function buildCheckoutPayload(Cart $cart): array
    {
        $itemList = [];

        foreach ($cart->getProducts() as $product) {
            $itemList[] = [
                'productId' => (string) $product['id_product'],
                'quantity'        => (int) $product['cart_quantity'],
            ];
        }

        return [
            'merchantApiKey'   => Configuration::get('RAPYDMNEE_API_KEY'),
            'itemList'         => $itemList,
            'source'           => 'PRESTASHOP',
            'name'             => 'prestashop-' . $this->context->shop->name, 
            'cartId'           => (int) $cart->id,
            'currencyISO'         => $this->context->currency->iso_code,
            'fees'             => (float) $cart->getOrderTotal(true, Cart::ONLY_SHIPPING),
            'validationAmount' => (float) $cart->getOrderTotal(),
            'returnURL'        => $this->context->link->getModuleLink('rapydmnee', 'return', [], true),
            'webhookURL'       => $this->context->link->getModuleLink('rapydmnee', 'webhook', [], true),        ];
    }

    private function createCheckoutSession(array $payload): array
    {
        $ch = curl_init($this->apiBaseUrl . '/checkout-session/create');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($curlError) {
            $this->showJsError('CURL Error: ' . $curlError);
            return [];
        }

        if ($httpCode !== 200) {
            $this->showJsError("HTTP Error: $httpCode", $response);
            return [];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->showJsError('JSON decode error: ' . json_last_error_msg(), $response);
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    private function showJsError(string $message, $data = null)
    {
        $dataStr = $data ? addslashes(json_encode($data)) : '';
        echo "<script>
            console.error('{$message}');
            " . ($dataStr ? "console.error('API Response: {$dataStr}');" : "") . "
            alert('{$message}');
        </script>";
    }
}
