<?php  

include dirname(__FILE__).'/../Models/user.php';
trait UserApis {
    
    public function addUser() {
        $firstName = $this->validateParameter('firstName', $this->param['firstName'], STRING, false);
        $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, false);
        $password = $this->validateParameter('password', $this->param['password'], STRING, false);
        $username = $this->validateParameter('userName', $this->param['userName'], STRING, false);
        $authorizations = $this->validateParameter('authorizations', $this->param['authorizations'], STRING, false);
        $gender = $this->validateParameter('gender', $this->param['gender'], STRING, false);
        $created_by = $this->validateParameter('created_by', $this->param['created_by'], INTEGER, false);
       

        $cust = new User;
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setUserName($username);
        $cust->setAuthorizations($authorizations);
        $cust->setGender($gender);
        $cust->setPassword(password_hash($password,PASSWORD_BCRYPT)); 
        $cust->setCreatedBy($created_by);
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "User created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllUsers() {
        
        $cust = new User;
        $users = $cust->getAllUsers();
        
        if(!is_array($users)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $users);
    }

    public function getAllUsersPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new User;
        $cust->setPage($page);
        $user = $cust->getUserDetailsByPage();
        

        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $user);
    }

    public function getUserById() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);

        $cust = new User;
        $cust->setId($userId);
        $user = $cust->getUserDetailsById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $user);
    }

    public function updateUser() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);
        $firstName = $this->validateParameter('firstName', $this->param['firstName'], STRING, false);
        $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, false);
        $username = $this->validateParameter('userName', $this->param['userName'], STRING, false);
        $authorizations = $this->validateParameter('authorizations', $this->param['authorizations'], STRING, false);
        $gender = $this->validateParameter('gender', $this->param['gender'], STRING, false);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, false);

        $cust = new User;
        $cust->setId($userId);
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setUserName($username);
        $cust->setAuthorizations($authorizations);
        $cust->setGender($gender);
        $cust->setUpdatedBy($updated_by);

        

        $user = $cust->getUserDetailsById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "User updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function updateStatus() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);
        $active = $this->validateParameter('active', $this->param['active'], INTEGER, false);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, false);

        $cust = new User;
        $cust->setId($userId);
        $cust->setActive($active);
        $cust->setUpdatedBy($updated_by);

        $user = $cust->getUserDetailsById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }else{
            if($active == 0 || $active == 1){
                if(!$cust->updateActive()) {
                    $message = 'Failed to update.';
                    $this->returnResponse(SUCCESS_WARNING, $message);
                } else {
                    if($active == 0){
                        $message = "User de-activated successfully.";
                    }
                    if($active == 1){
                        $message = "User activated successfully.";
                    }
                    
                }
            }else{
                $message = "You can only use 0 or 1 for the param.";
            }
            
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }


    public function restPassword() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, false);
        
        $cust = new User;
        $cust->setId($userId);
        $cust->setPassword(password_hash("123456789",PASSWORD_BCRYPT));
        $cust->setUpdatedBy($updated_by);

        $user = $cust->getUserDetailsById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }else{
            if(!$cust->updatePasswordDefault()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "User password successfully rest to default.";
                
            }
            
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function changePassword() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);
        $old_password = $this->validateParameter('old_password', $this->param['old_password'], STRING, false);
        $new_password = $this->validateParameter('new_password', $this->param['new_password'], STRING, false);
        
        $cust = new User;
        $cust->setId($userId);
        $cust->setNewPassword(password_hash($new_password,PASSWORD_BCRYPT));
        $cust->setOldPassword($old_password);
       
        $user = $cust->getUserPasswordById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User details not found.']);
        }else{

            if(password_verify($old_password, $user['password'])){
                if(!$cust->updatePasswordChange()) {
                    $message = 'Failed to update.';
                    $this->returnResponse(SUCCESS_WARNING, $message);
                    
                } else {
                    $message = "User password successfully updated.";
                    
                }
            }else{
                $message = "User old password is incorrect.";
                $this->returnResponse(SUCCESS_WARNING, $message);
            }


            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

    public function deleteUser() {
        $userId = $this->validateParameter('userId', $this->param['userId'], INTEGER);

        $cust = new User;
        $cust->setId($userId);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "User deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>