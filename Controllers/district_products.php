<?php  

include dirname(__FILE__).'/../Models/district_products.php';
trait DistrictProductsApis {
    
    public function addDistrictProduct() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        

        $cust = new DistrictProduct;
        $cust->setName($name);
        
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "District product created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllDistrictProducts() {
        
        $cust = new DistrictProduct;
        $currencies = $cust->getAllDistrictProducts();
        if(!is_array($currencies)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District product details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $currencies);
    }

    public function getDistrictProductById() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER);

        $cust = new DistrictProduct;
        $cust->setId($product_id);
        $product = $cust->getDistrictProductDetailsById();
        if(!is_array($product)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District product details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $currency);
    }

    public function updateDistrictProduct() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER);
        $name = $this->validateParameter('name', $this->param['name'], STRING);
        
        $cust = new DistrictProduct;
        $cust->setId($product_id);
        $cust->setName($name);
        

        $product = $cust->getDistrictProductDetailsById();
        if(!is_array($product)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'District product details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "District product updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

 

    public function deleteDistrictProduct() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER);

        $cust = new DistrictProduct;
        $cust->setId($product_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "District product deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>