<?php 
	class Product {
		private $id;
		private $name;
		private $description;
		private $company;
		private $code;
		private $value;
		private $currency;
		private $type;
		private $createdBy;
		private $page;
		
		private $tableName = 'products';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setPage($page) { $this->page = $page; }
		function getPage() { return $this->page; }
		function setName($name) { $this->name = $name;}
		function getName() { return $this->name; }
		function setDescription($description) { $this->description = $description;}
		function getDescription() { return $this->description; }
		function setCompany($company) { $this->company = $company;}
		function getCompany() { return $this->company; }
		function setCode($code) { $this->code = $code;}
		function getCode() { return $this->code; }
		function setValue($value) { $this->value = $value;}
		function getValue() { return $this->value; }
		function setCurrency($currency) { $this->currency = $currency;}
		function getCurrency() { return $this->currency; }
		function setType($type) { $this->type = $type;}
		function getType() { return $this->type; }
		function setCreatedBy($createdBy) { $this->createdBy = $createdBy;}
		function getCreatedBy() { return $this->createdBy; }
		function setUpdatedBy($updatedBy) { $this->updatedBy = $updatedBy;}
		function getUpdatedBy() { return $this->updatedBy; }
		
		
		
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllProducts() {
			$stmt = $this->dbConn->prepare("SELECT p.id,p.product_name,p.product_description,p.product_code,p.product_value,p.company AS company_id, c.name AS company_name,cu.code AS currency_code,pt.name AS product_type_name FROM products p
			INNER JOIN company c ON c.id = p.company
			INNER JOIN currencies cu ON cu.id = p.product_currency
			INNER JOIN product_types pt ON pt.id = p.product_type");
			$stmt->execute();
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $products;
		}

		public function getProductById() {

			$sql = "SELECT p.id,p.product_name,p.product_description,p.product_code,p.product_value,p.company AS company_id, c.name AS company_name,cu.code AS currency_code,pt.name AS product_type_name FROM products p
			INNER JOIN company c ON c.id = p.company
			INNER JOIN currencies cu ON cu.id = p.product_currency
			INNER JOIN product_types pt ON pt.id = p.product_type
			WHERE p.id = :product_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':product_id', $this->id);
			$stmt->execute();
			$product = $stmt->fetch(PDO::FETCH_ASSOC);
			return $product;
		}

		public function getProductsByCompany() {

			$sql = "SELECT p.id,p.product_name,p.product_description,p.product_code,p.product_value,p.company AS company_id, c.name AS company_name,cu.code AS currency_code,pt.name AS product_type_name FROM products p
			INNER JOIN company c ON c.id = p.company
			INNER JOIN currencies cu ON cu.id = p.product_currency
			INNER JOIN product_types pt ON pt.id = p.product_type
			WHERE p.company = :company";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':company', $this->company);
			$stmt->execute();
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $products;
		}

		public function getAllProductsPaged() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM products');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch products
			
			$sql = "SELECT p.id,p.product_name,p.product_description,p.product_code,p.product_value,p.company AS company_id, c.name AS company_name,cu.code AS currency_code,pt.name AS product_type_name FROM products p
					INNER JOIN company c ON c.id = p.company
					INNER JOIN currencies cu ON cu.id = p.product_currency
					INNER JOIN product_types pt ON pt.id = p.product_type
					ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all products for current page
			$products = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$product_array = ['currentPage' => $page,'totalPages' => $total_pages,'products' => $products];
			return $product_array;
		}

			

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(product_name,product_description,product_code,company,product_value,product_currency,product_type,created_by) VALUES(:product_name,:product_description,:product_code,:company,:product_value,:product_currency,:product_type,:created_by)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':product_name', $this->name);
			$stmt->bindParam(':product_description', $this->description);
			$stmt->bindParam(':product_code', $this->code);
			$stmt->bindParam(':company', $this->company);
			$stmt->bindParam(':product_value', $this->value);
			$stmt->bindParam(':product_currency', $this->currency);
			$stmt->bindParam(':product_type', $this->type);
			$stmt->bindParam(':created_by', $this->createdBy);
				
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getName()) {
				$sql .=	" product_name = '" . $this->getName() . "',";
			}
			if( null != $this->getDescription()) {
				$sql .=	" product_description = '" . $this->getDescription() . "',";
			}
			if( null != $this->getCode()) {
				$sql .=	" product_code = '" . $this->getCode() . "',";
			}
			if( null != $this->getValue()) {
				$sql .=	" product_value = '" . $this->getValue() . "',";
			}
			if( null != $this->getCurrency()) {
				$sql .=	" product_currency = '" . $this->getCurrency() . "',";
			}
			if( null != $this->getUpdatedBy()) {
				$sql .=	" updated_by = '" . $this->getUpdatedBy() . "',";
			}
			if( null != $this->getType()) {
				$sql .=	" product_type = '" . $this->getType() . "'";
			}
			
					
			$sql .=	" WHERE id = :product_id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':product_id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}


		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :product_id');
			$stmt->bindParam(':product_id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>