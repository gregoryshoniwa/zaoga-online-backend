<?php  

include dirname(__FILE__).'/../Models/member.php';
trait MemberApis {
    
    public function addMember() {
        $firstName = $this->validateParameter('firstName', $this->param['firstName'], STRING, false);
        $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, false);
        $gender = $this->validateParameter('gender', $this->param['gender'], INTEGER, false);
        $position = $this->validateParameter('position_id', $this->param['position_id'], INTEGER, false);
        $assembly_id = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER, false);
        $national_id = $this->validateParameter('national_id', $this->param['national_id'], STRING, false);
        $created_by = $this->validateParameter('created_by', $this->param['created_by'], INTEGER, false);
       

        $cust = new Member;
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setGender($gender);
        $cust->setAssemblyId($assembly_id);
        $cust->setPosition($position);
        $cust->setNationalId($national_id);
        $cust->setCreatedBy($created_by);
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "Member created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllMembers() {
        
        $cust = new Member;
        $members = $cust->getAllMembers();
        if(!is_array($members)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Member details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $members);
    }

    public function getAllMembersPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new Member;
        $cust->setPage($page);
        $user = $cust->getMemberDetailsByPage();
        

        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Member details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $user);
    }

    public function getMemberById() {
        $memberId = $this->validateParameter('memberId', $this->param['memberId'], INTEGER);

        $cust = new Member;
        $cust->setId($memberId);
        $member = $cust->getMemberDetailsById();
        if(!is_array($member)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Member details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $member);
    }

    public function updateMember() {
        $memberId = $this->validateParameter('memberId', $this->param['memberId'], INTEGER);
        $firstName = $this->validateParameter('firstName', $this->param['firstName'], STRING, false);
        $lastName = $this->validateParameter('lastName', $this->param['lastName'], STRING, false);
        $position = $this->validateParameter('position_id', $this->param['position_id'], INTEGER, false);
        $gender = $this->validateParameter('gender', $this->param['gender'], INTEGER, false);
        // $assembly = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER, false);
        $national_id = $this->validateParameter('national_id', $this->param['national_id'], STRING, false);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, false);

        $cust = new Member;
        $cust->setId($memberId);
        $cust->setFirstName($firstName);
        $cust->setLastName($lastName);
        $cust->setPosition($position);
        $cust->setGender($gender);
        // $cust->setAssemblyId($assembly);
        $cust->setNationalId($national_id);
        $cust->setUpdatedBy($updated_by);

        $member = $cust->getMemberDetailsById();
        if(!is_array($member)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Member details not found.']);
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

    public function updateMemberStatus() {
        $memberId = $this->validateParameter('memberId', $this->param['memberId'], INTEGER);
        $active = $this->validateParameter('active', $this->param['active'], INTEGER, false);
        $updated_by = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, false);

        $cust = new Member;
        $cust->setId($memberId);
        $cust->setActive($active);
        $cust->setUpdatedBy($updated_by);

        $user = $cust->getMemberDetailsById();
        if(!is_array($user)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Member details not found.']);
        }else{
            if($active == 0 || $active == 1){
                if(!$cust->updateActive()) {
                    $message = 'Failed to update.';
                    $this->returnResponse(SUCCESS_WARNING, $message);
                } else {
                    if($active == 0){
                        $message = "Member de-activated successfully.";
                    }
                    if($active == 1){
                        $message = "Member activated successfully.";
                    }
                    
                }
            }else{
                $message = "You can only use 0 or 1 for the param.";
            }
            
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

 

    public function deleteMember() {
        $memberId = $this->validateParameter('memberId', $this->param['memberId'], INTEGER);

        $cust = new Member;
        $cust->setId($memberId);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "Member deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>