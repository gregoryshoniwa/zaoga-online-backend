<?php  

include dirname(__FILE__).'/../Models/districts.php';
trait DistrictApis {
     
    public function addAssembly() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $province = $this->validateParameter('province_id', $this->param['province_id'], INTEGER, false);
        $national = $this->validateParameter('national_id', $this->param['national_id'], INTEGER, false);
        

        $cust = new District;
        $cust->setName($name);
        $cust->setProvinceId($province);
        $cust->setNationalId($national);
        
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "District created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllDistricts() {
        
        $cust = new District;
        $currencies = $cust->getAllCurrencies();
        if(!is_array($currencies)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $currencies);
    }

    public function getDistrictById() {
        $district_id = $this->validateParameter('district_id', $this->param['district_id'], INTEGER);

        $cust = new District;
        $cust->setId($district_id);
        $currency = $cust->getAllDistrictsById();
        if(!is_array($currency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }


    public function getDistrctsByProvince() {
        $province_id = $this->validateParameter('province_id', $this->param['province_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($province_id);
        $assembly = $cust->getAllDistrictsByProvince();
        if(!is_array($currassemblyency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function getDistrictsByNation() {
        $national_id = $this->validateParameter('national_id', $this->param['national_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($national_id);
        $assembly = $cust->getAllDistrictsByNation();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }


    public function updateDistrict() {
        $district_id = $this->validateParameter('district_id', $this->param['district_id'], INTEGER);
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $province = $this->validateParameter('province_id', $this->param['province_id'], INTEGER, false);
        $national = $this->validateParameter('national_id', $this->param['national_id'], INTEGER, false);
        
        
        $cust = new District;
        $cust->setId($district_id);
        $cust->setName($name);
        $cust->setProvinceId($province);
        $cust->setNationalId($national);

        $assembly = $cust->getAllDistrictsById();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "District updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }


    public function deleteDistrict() {
        $district_id = $this->validateParameter('district_id', $this->param['district_id'], INTEGER);

        $cust = new District;
        $cust->setId($district_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $Currency = "District deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>