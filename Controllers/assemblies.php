<?php  

include dirname(__FILE__).'/../Models/assemblies.php';
trait AssemblyApis {
     
    public function addAssembly() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $region = $this->validateParameter('region_id', $this->param['region_id'], INTEGER, false);
        $district = $this->validateParameter('district_id', $this->param['district_id'], INTEGER, false);
        $province = $this->validateParameter('province_id', $this->param['province_id'], INTEGER, false);
        $national = $this->validateParameter('national_id', $this->param['national_id'], INTEGER, false);
        

        $cust = new Assembly;
        $cust->setName($name);
        $cust->setRegionId($region);
        $cust->setDistrictId($district);
        $cust->setProvinceId($province);
        $cust->setNationalId($national);
        
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "Assembly created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllAssemblies() {
        
        $cust = new Assembly;
        $currencies = $cust->getAllCurrencies();
        if(!is_array($currencies)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $currencies);
    }

    public function getAssemblyById() {
        $assembly_id = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($assembly_id);
        $currency = $cust->getAllAssembliesById();
        if(!is_array($currency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function getAssemblyByRegion() {
        $region_id = $this->validateParameter('region_id', $this->param['region_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($region_id);
        $assembly = $cust->getAllAssembliesByRegion();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function getAssemblyByDistrict() {
        $district_id = $this->validateParameter('district_id', $this->param['district_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($district_id);
        $assembly = $cust->getAllAssembliesByDistrict();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function getAssemblyByProvince() {
        $province_id = $this->validateParameter('province_id', $this->param['province_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($province_id);
        $assembly = $cust->getAllAssembliesByProvince();
        if(!is_array($currassemblyency)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function getAssemblyByNation() {
        $national_id = $this->validateParameter('national_id', $this->param['national_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($national_id);
        $assembly = $cust->getAllAssembliesByNation();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Assembly details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }


    public function updateAssembly() {
        $assembly_id = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER);
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $region = $this->validateParameter('region_id', $this->param['region_id'], INTEGER, false);
        $district = $this->validateParameter('district_id', $this->param['district_id'], INTEGER, false);
        $province = $this->validateParameter('province_id', $this->param['province_id'], INTEGER, false);
        $national = $this->validateParameter('national_id', $this->param['national_id'], INTEGER, false);
        
        
        $cust = new Assembly;
        $cust->setId($assembly_id);
        $cust->setName($name);
        $cust->setRegionId($region);
        $cust->setDistrictId($district);
        $cust->setProvinceId($province);
        $cust->setNationalId($national);

        $assembly = $cust->getMemberDetailsById();
        if(!is_array($assembly)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Currency details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Assembly updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

 

    public function deleteAssembly() {
        $assembly_id = $this->validateParameter('assembly_id', $this->param['assembly_id'], INTEGER);

        $cust = new Assembly;
        $cust->setId($assembly_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $Currency = "Assembly deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>