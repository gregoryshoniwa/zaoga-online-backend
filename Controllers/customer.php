<?php  

    
    include dirname(__FILE__).'/../Models/customer.php';
    trait CustomerApis {
        
        public function addCustomer() {
			$firstName = $this->validateParameter('fistName', $this->param['firstName'], STRING, false);
            $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$addr = $this->validateParameter('address', $this->param['address'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);
            $position = $this->validateParameter('position', $this->param['position'], INTEGER, false);
 
 
			$cust = new Customer;
			$cust->setFirstName($firstName);
            $cust->setLastName($lastName);
			$cust->setEmail($email);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
            $cust->setPosition($position);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));

			if(!$cust->insert()) {
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
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}
           

			$this->returnResponse(SUCCESS_RESPONSE, $customers);
		}

        public function getCustomerById() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);
			$customer = $cust->getCustomerDetailsById();
			if(!is_array($customer)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}

			$this->returnResponse(SUCCESS_RESPONSE, $customer);
		}

		public function updateCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$addr = $this->validateParameter('address', $this->param['address'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setId($customerId);
			$cust->setName($name);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setUpdatedBy($this->userId);
			$cust->setUpdatedOn(date('Y-m-d'));

			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);

			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
    }
    
    
?>