<?php

class RapydmneeReturnModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        // Just show a "processing" or "thank you" page
        $this->setTemplate('module:rapydmnee/views/templates/front/return.tpl');
    }
}
