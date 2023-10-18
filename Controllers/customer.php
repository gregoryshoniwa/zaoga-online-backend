<?php  
 
    
include dirname(__FILE__).'/../Models/customer.php';
trait CustomerApis {
     
 
    public function addCustomer() {
        $firstName = $this->validateParameter('first_name', $this->param['first_name'], STRING, true);
        $lastName = $this->validateParameter('last_name', $this->param['last_name'], STRING, true);
        $password = $this->validateParameter('password', $this->param['password'], STRING, true);
        $username = $this->validateParameter('user_name', $this->param['user_name'], STRING, true);
        $active = $this->validateParameter('active', $this->param['active'], INTEGER, true);
        $gender = $this->validateParameter('gender', $this->param['gender'], INTEGER, true);
        $company = $this->validateParameter('company', $this->param['company'], STRING, true);
        $created_by = $this->validateParameter('created_by', $this->param['created_by'], INTEGER, true);
       
        //Customer creation
        $cust = new Customer;
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setUserName($username);
        $cust->setActive($active);
        $cust->setGender($gender);
        $cust->setCompany($company);
        $cust->setPassword(password_hash($password,PASSWORD_BCRYPT)); 
        $cust->setCreatedBy($created_by);
      
        $lastCompanyId = $cust->insertCompany();
        //set created Company id
        $cust->setCompany($lastCompanyId);

        //create Customer
        $lastInsertedId = $cust->insert();

        //API creation 
        $secret = md5(time() . $lastInsertedId);
        $cust->setId($lastInsertedId);
        $cust->setLiveSecret(password_hash($secret,PASSWORD_BCRYPT));
        $cust->setDevSecret($secret);
        $cust->setDescription("New Customer APIs");

        if(!$lastInsertedId) {
            $message = 'Failed to insert.';
        } else {
            if(!$cust->insertSecret()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Customer created successfully.";
            }
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function addCustomerIn() {
        $firstName = $this->validateParameter('first_name', $this->param['first_name'], STRING, true);
        $lastName = $this->validateParameter('last_name', $this->param['last_name'], STRING, true);
        $password = $this->validateParameter('password', $this->param['password'], STRING, true);
        $username = $this->validateParameter('user_name', $this->param['user_name'], STRING, true);
        $active = $this->validateParameter('active', $this->param['active'], INTEGER, true);
        $gender = $this->validateParameter('gender', $this->param['gender'], INTEGER, true);
        $company = $this->validateParameter('company', $this->param['company'], INTEGER, true);
        $created_by = $this->validateParameter('created_by', $this->param['created_by'], INTEGER, true);
       
        //Customer creation
        $cust = new Customer;
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setUserName($username);
        $cust->setActive($active);
        $cust->setGender($gender);
        $cust->setCompany($company);
        $cust->setPassword(password_hash($password,PASSWORD_BCRYPT)); 
        $cust->setCreatedBy($created_by);
      
        //create Customer
        $lastInsertedId = $cust->insert();


        if(!$lastInsertedId) {
            $message = 'Failed to insert.';
        } else {
            $message = "Customer created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllCustomers() {
        
        $cust = new Customer;
        $customers = $cust->getAllCustomers();
        
        if(!is_array($customers)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $customers);
    }

    public function getAllCustomersPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new Customer;
        $cust->setPage($page);
        $customer = $cust->getCustomerDetailsByPage();
        

        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $customer);
    }

    public function getCustomerById() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

        $cust = new Customer;
        $cust->setId($customerId);
        $customer = $cust->getCustomerDetailsById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $customer);
    }

    public function updateCustomer() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
        $firstName = $this->validateParameter('firstName', $this->param['firstName'], STRING, true);
        $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, true);
        $username = $this->validateParameter('userName', $this->param['userName'], STRING, true);
        $company = $this->validateParameter('company', $this->param['company'], STRING, true);
        $gender = $this->validateParameter('gender', $this->param['gender'], STRING, true);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, true);

        $cust = new Customer;
        $cust->setId($customerId);
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setUserName($username);
        $cust->setCompany($company);
        $cust->setGender($gender);
        $cust->setUpdatedBy($updated_by);

        

        $customer = $cust->getCustomerDetailsById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Customer updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function updateCustomerStatus() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER,true);
        $active = $this->validateParameter('active', $this->param['active'], INTEGER, true);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, true);

        $cust = new Customer;
        $cust->setId($customerId);
        $cust->setActive($active);
        $cust->setUpdatedBy($updated_by);

        $customer = $cust->getCustomerDetailsById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }else{
            if($active == 0 || $active == 1){
                if(!$cust->updateActive()) {
                    $message = 'Failed to update.';
                    $this->returnResponse(SUCCESS_WARNING, $message);
                } else {
                    if($active == 0){
                        $message = "Customer de-activated successfully.";
                    }
                    if($active == 1){
                        $message = "Customer activated successfully.";
                    }
                    
                }
            }else{
                $message = "You can only use 0 or 1 for the param.";
            }
            
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }


    public function restPasswordCustomer() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER,true);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, true);
        
        $cust = new Customer;
        $cust->setId($customerId);
        $cust->setPassword(password_hash("123456789",PASSWORD_BCRYPT));
        $cust->setUpdatedBy($updated_by);

        $customer = $cust->getCustomerDetailsById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }else{
            if(!$cust->updatePasswordDefault()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Customer password successfully rest to default.";
                
            }
            
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function changeCustomerPassword() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
        $old_password = $this->validateParameter('oldPassword', $this->param['oldPassword'], STRING, true);
        $new_password = $this->validateParameter('newPassword', $this->param['newPassword'], STRING, true);
        
        $cust = new Customer;
        $cust->setId($customerId);
        $cust->setNewPassword(password_hash($new_password,PASSWORD_BCRYPT));
        $cust->setOldPassword($old_password);
       
        $customer = $cust->getCustomerPasswordById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }else{

            if(password_verify($old_password, $customer['password'])){
                if(!$cust->updatePasswordChange()) {
                    $message = 'Failed to update.';
                    $this->returnResponse(SUCCESS_WARNING, $message);
                    
                } else {
                    $message = "Customer password successfully updated.";
                    
                }
            }else{
                $message = "Customer old password is incorrect.";
                $this->returnResponse(SUCCESS_WARNING, $message);
            }


            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function newCustomerSecret() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER, true);
        $description = $this->validateParameter('description', $this->param['description'], STRING, true);
        
        $cust = new Customer;
        $secret = md5(time() . $customerId);
        $cust->setId($customerId);
        $cust->setLiveSecret(password_hash($secret,PASSWORD_BCRYPT));
        $cust->setDevSecret($secret);
        $cust->setDescription($description);
        
        $customer = $cust->getCustomerPasswordById();
        if(!is_array($customer)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Customer details not found.']);
        }else{

            if(!$cust->insertSecret()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Customer created successfully.";
            }
           
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function deleteCustomer() {
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

        $cust = new Customer;
        $cust->setId($customerId);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "Customer deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
	
}
    
    
?>