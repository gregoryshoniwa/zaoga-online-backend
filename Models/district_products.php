<?php 
	class DistrictProduct {
		private $id;
		private $name;
		private $code;
		private $symbol;
		
		private $tableName = 'district_products';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name;}
		function getName() { return $this->name; }
		
		
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllDistrictProducts() {
			$stmt = $this->dbConn->prepare("SELECT * FROM district_products");
			$stmt->execute();
			$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $members;
		}

		public function getDistrictProductDetailsById() {

			$sql = "SELECT * FROM district_products d WHERE d.id = :product_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':product_id', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

			

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, name) VALUES(null, :name)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
				
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getName()) {
				$sql .=	" name = '" . $this->getName() . "',";
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