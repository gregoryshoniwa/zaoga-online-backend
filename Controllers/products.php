<?php  

include dirname(__FILE__).'/../Models/products.php';
trait ProductsApis {
    
    public function addProduct() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, true);
        $description = $this->validateParameter('description', $this->param['description'], STRING, true);
        $code = $this->validateParameter('code', $this->param['code'], STRING, true);
        $company = $this->validateParameter('company', $this->param['company'], STRING, true);
        $value = $this->validateParameter('value', $this->param['value'], STRING, true);
        $currency = $this->validateParameter('currency', $this->param['currency'], STRING, true);
        $type = $this->validateParameter('type', $this->param['type'], STRING, true);
        $createdBy = $this->validateParameter('created_by', $this->param['created_by'], STRING, true);
       
        

        $cust = new Product;
        $cust->setName($name);
        $cust->setDescription($description);
        $cust->setCode($code);
        $cust->setCompany($company);
        $cust->setValue($value);
        $cust->setCurrency($currency);
        $cust->setType($type);
        $cust->setCreatedBy($createdBy);
        
       
        if(!$cust->insert()) {
            $message = 'Failed to insert.';
        } else {
            $message = "Product created successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }

    public function getAllProducts() {
        
        $cust = new Product;
        $currencies = $cust->getAllProducts();
        if(!is_array($currencies)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Product details not found.']);
        }
       

        $this->returnResponse(SUCCESS_RESPONSE, $currencies);
    }

    public function getAllProductsPaged() {
        $page = $this->validateParameter('page', $this->param['page'], INTEGER);

        $cust = new Product;
        $cust->setPage($page);
        $products = $cust->getAllProductsPaged();
        

        if(!is_array($products)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Products details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $products);
    }

    public function getProductById() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER);

        $cust = new Product;
        $cust->setId($product_id);
        $product = $cust->getProductById();
        if(!is_array($product)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Product details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $product);
    }

    public function getProductsByCompany() {
        $company_id = $this->validateParameter('company_id', $this->param['company_id'], INTEGER);

        $cust = new Product;
        $cust->setCompany($company_id);
        $product = $cust->getProductsByCompany();
        if(!is_array($product)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Product details not found.']);
        }

        $this->returnResponse(SUCCESS_RESPONSE, $product);
    }

    public function updateProduct() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER, true);
        $name = $this->validateParameter('name', $this->param['name'],STRING, true);
        $description = $this->validateParameter('description', $this->param['description'], STRING, true);
        $code = $this->validateParameter('code', $this->param['code'], STRING, true);
        $value = $this->validateParameter('value', $this->param['value'], STRING, true);
        $currency = $this->validateParameter('currency', $this->param['currency'], INTEGER, true);
        $type = $this->validateParameter('type', $this->param['type'], INTEGER, true);
        $updatedBy = $this->validateParameter('updated_by', $this->param['updated_by'], INTEGER, true);
        
        $cust = new Product;
        $cust->setId($product_id);
        $cust->setName($name);
        $cust->setDescription($description);
        $cust->setCode($code);
        $cust->setValue($value);
        $cust->setCurrency($currency);
        $cust->setType($type);
        $cust->setUpdatedBy($updatedBy);
        

        $product = $cust->getProductById();
        if(!is_array($product)) {
            $this->returnResponse(SUCCESS_WARNING, ['message' => 'Product details not found.']);
        }else{
            if(!$cust->update()) {
                $message = 'Failed to update.';
                $this->returnResponse(SUCCESS_WARNING, $message);
            } else {
                $message = "Product updated successfully.";
            }
    
            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        
    }

 

    public function deleteProduct() {
        $product_id = $this->validateParameter('product_id', $this->param['product_id'], INTEGER);

        $cust = new Product;
        $cust->setId($product_id);

        if(!$cust->delete()) {
            $message = 'Failed to delete.';
            $this->returnResponse(SUCCESS_WARNING, $message);
        } else {
            $message = "Product deleted successfully.";
        }

        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>