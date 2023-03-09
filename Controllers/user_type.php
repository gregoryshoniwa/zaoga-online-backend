<?php  

include dirname(__FILE__).'/../Models/user_type.php';
trait UserTypesApis {
    
    public function addUserType() {
        $user_type = $this->validateParameter('user_type', $this->param['user_type'], STRING, false);
        $cust = new UserType;
        $cust->setUserType($user_type);
        
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "User type created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    

    public function getAllUserTypesPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new UserType;
        $cust->setPage($page);
        $user = $cust->getUserTypeDetailsByPage();
        

        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User type details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $user);
    }

    public function getUserTypeById() {
        $user_type_id = $this->validateParameter('user_type_id', $this->param['user_type_id'], INTEGER);

        $cust = new UserType;
        $cust->setId($user_type_Id);
        $member = $cust->getUserTypeDetailsByPage();
        if(!is_array($member)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User type details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $member);
    }

    public function updateUserType() {
        $user_type_id = $this->validateParameter('user_type_id', $this->param['user_type_id'], INTEGER);
        $user_type = $this->validateParameter('user_type', $this->param['user_type'], STRING, false);
        
        $cust = new UserType;
        $cust->setId($user_type_id);
        $cust->setUserType($user_type);
       

        $member = $cust->getUserTypeDetailsById();
        if(!is_array($member)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'User type details not found.']);
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


    public function deleteUserType() {
        $user_type_id = $this->validateParameter('user_type_id', $this->param['user_type_id'], INTEGER);

        $cust = new UserType;
        $cust->setId($user_type_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "User type deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>