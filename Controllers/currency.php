<?php  

include dirname(__FILE__).'/../Models/currency.php';
trait CurrencyApis {
    
    public function addCurrency() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $code = $this->validateParameter('code', $this->param['code'], STRING, false);
        $symbol = $this->validateParameter('symbol', $this->param['symbol'], STRING, false);
       

        $cust = new Currency;
        $cust->setName($name);
        $cust->setCode($code);
        $cust->setSymbol($symbol);
        
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "Currency created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllCurrencies() {
        
        $cust = new Currency;
        $currencies = $cust->getAllCurrencies();
        if(!is_array($currencies)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Currency details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $currencies);
    }

    public function getCurrencyById() {
        $currency_id = $this->validateParameter('currency_id', $this->param['currency_id'], INTEGER);

        $cust = new Currency;
        $cust->setId($currency_id);
        $currency = $cust->getCurrencyDetailsById();
        if(!is_array($currency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Currency details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function updateCurrency() {
        $currency_id = $this->validateParameter('currency_id', $this->param['currency_id'], INTEGER);
        $name = $this->validateParameter('name', $this->param['name'], STRING);
        $code = $this->validateParameter('code', $this->param['code'], STRING, false);
        $symbol = $this->validateParameter('symbol', $this->param['symbol'], STRING, false);
        
        $cust = new Currency;
        $cust->setId($currency_id);
        $cust->setName($name);
        $cust->setCode($code);
        $cust->setSymbol($symbol);

        $currency = $cust->getMemberDetailsById();
        if(!is_array($currency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Currency details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Currency updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

 

    public function deleteCurrency() {
        $currency_id = $this->validateParameter('currency_id', $this->param['currency_id'], INTEGER);

        $cust = new Currency;
        $cust->setId($currency_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $Currency = "Currency deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>