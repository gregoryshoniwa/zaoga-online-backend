<?php  

include dirname(__FILE__).'/../Models/district_transactions.php';
trait DistrictTransactionsApis {
     
    public function addDistrictTransaction() {
        $user_id = $this->validateParameter('user_id', $this->param['user_id'], INTEGER, false);
        $member_id = $this->validateParameter('member_id', $this->param['member_id'], INTEGER, false);
        $district_product_id = $this->validateParameter('district_product_id', $this->param['district_product_id'], INTEGER, false);
        $currency = $this->validateParameter('currency', $this->param['currency'], STRING, false);
        $assembly_id = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER, false);
        $amount = $this->validateParameter('amount', $this->param['amount'], STRING, false);
        $form_id = $this->validateParameter('form_id', $this->param['form_id'], STRING, false);
       

        $cust = new DistrictTransactions;
        $cust->setUserId($user_id);
        $cust->setMemberId($member_id);
        $cust->setDistrictProductId($district_product_id);
        $cust->setCurrency($currency);
        $cust->setAssemblyId($assembly_id);
        $cust->setAmount($amount);
        $cust->setFormId($form_id);
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "Form created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    

    public function getAllDistrictTransactionsPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new DistrictTransactions;
        $cust->setPage($page);
        $user = $cust->getDistrictTransactionsByPage();
        

        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Form details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $user);
    }

    public function getAllDistrictTransactionsById() {
        $form_id = $this->validateParameter('form_id', $this->param['form_id'], STRING);

        $cust = new DistrictTransactions;
        $cust->setId($form_id);
        $form = $cust->getDistrictTransactionsById();
        if(!is_array($form)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Form details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $member);
    }

    public function updateDistrictTransaction() {
        $form_id = $this->validateParameter('form_id', $this->param['form_id'], STRING);
        $recieved_by = $this->validateParameter('recieved_by', $this->param['recieved_by'], STRING, false);
        
        $cust = new DistrictTransactions;
        $cust->setFormId($form_id);
        $cust->setRecievedBy($recieved_by);
        

        $form = $cust->getDistrictTransactionsById();
        if(!is_array($form)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Form details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Member updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

     

    public function deleteDistrictTransaction() {
        $form_id = $this->validateParameter('form_id', $this->param['form_id'], INTEGER);

        $cust = new DistrictTransactions;
        $cust->setId($form_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "Form deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>