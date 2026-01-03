# Rapyd MNEE PrestaShop Payment Module

This is a **PrestaShop extension** that integrates the **Rapyd MNEE Payment Gateway** with your PrestaShop store.

A demo store is hosted on [http://rapydmnee.duckdns.org:9005/prestashop/index.php](http://rapydmnee.duckdns.org:9005/prestashop/index.php) to try the checkout integration.

With this module, your store can:

- Accept payments via Rapyd MNEE
- Use the Rapyd MNEE dashboard for product and subscription management
- Seamlessly connect with the backend services for balance, transactions, and checkout

## Prerequisites

Before installing this module, make sure the following services are running:

- **Payment Service**: [https://github.com/rapyd-mnee-payment-gateway/paymentservice.git](https://github.com/rapyd-mnee-payment-gateway/paymentservice.git)
- **MNEE Service**: [https://github.com/rapyd-mnee-payment-gateway/mnee-service](https://github.com/rapyd-mnee-payment-gateway/mnee-service)
- **Rapyd MNEE Dashboard**: [https://github.com/rapyd-mnee-payment-gateway/mnee-stripe](https://github.com/rapyd-mnee-payment-gateway/mnee-stripe)

These services handle product creation, checkout sessions, subscriptions, and API integrations.

## Installation

1. Download the latest release ZIP of this module from the repository's [Releases](#).
2. Log in to your **PrestaShop Back Office**.
3. Navigate to **Modules > Module Manager**.
4. Click **Upload a module** and select the downloaded ZIP file.
5. Once uploaded, install the module from the Module Manager.

> After installation, the module will appear in your list of payment options and can be configured using your Rapyd MNEE credentials.

## Usage

- Ensure the **Rapyd MNEE dashboard**, **payment service**, and **MNEE service** are running.
- Configure the module in PrestaShop with your merchant details and API keys.
- Your PrestaShop store will now be able to create checkout sessions and process payments through Rapyd MNEE.
- Check [https://devdocs.prestashop-project.org/9/basics/installation/](https://devdocs.prestashop-project.org/9/basics/installation/) to set up prestashop store
