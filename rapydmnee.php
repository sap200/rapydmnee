<?php

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class Rapydmnee extends PaymentModule 
{


    public function __construct() 
    {
        $this->name = 'rapydmnee';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Saptarsi Halder';

        parent::__construct();
        $this->displayName = $this->l('Rapyd MNEE extension');
        $this->description = $this->l('Prestashop module for Rapyd MNEE');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '9.5');

    }

    public function install() 
    {
        return parent::install()
        && $this->registerHook('displayPaymentReturn')
        && $this->registerHook('paymentOptions');
    }

    public function uninstall() 
    {
        return parent::uninstall();
    }

    public function hookDisplayPaymentReturn() 
    {
        $this->context->smarty->assign(array(

        ));
        return $this->display(__FILE__, 'views/templates/hook/return.tpl');
    }

    public function hookPaymentOptions() 
    {
        $rapydmneeoptions = new PaymentOption();
        $rapydmneeoptions->setModuleName($this->name)
            ->setCallToActionText('Pay by Rapyd MNEE')
            ->setAdditionalInformation('Rapyd MNEE allows you to pay using $MNEE cryptocurrency.')
            ->setAction($this->context->link->getModuleLink($this->name, 'payment'));      
            
        return [$rapydmneeoptions];
    }

    public function postProcess()
    {
        $linkData = json_encode($this->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo '<script>';
        echo "console.log('Link Object:', $linkData);";
        echo '</script>';
    }

    public function getContent()
    {
        $output = '';
    
        // STEP 1 — Handle form submission
        if (Tools::isSubmit('submitRapydmneeConfig')) {
    
            // STEP 2 — Read & trim value
            $apiKey = trim((string) Tools::getValue('RAPYDMNEE_API_KEY'));
    
            // STEP 3 — Validate
            if (empty($apiKey)) {
                $output .= $this->displayError(
                    $this->l('Merchant API Key cannot be empty')
                );
            } else {
                // STEP 4 — Save to database
                Configuration::updateValue('RAPYDMNEE_API_KEY', $apiKey);
    
                // STEP 5 — Success message
                $output .= $this->displayConfirmation(
                    $this->l('API Key saved successfully')
                );
            }
        }
    
        // STEP 6 — Assign value to Smarty
        $this->context->smarty->assign([
            'api_key' => Configuration::get('RAPYDMNEE_API_KEY'),
        ]);
    
        // STEP 7 — Render template
        return $output . $this->display(
            __FILE__,
            'views/templates/admin/config.tpl'
        );
    }    

}