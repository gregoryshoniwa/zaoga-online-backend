<?php 
	class Customer {
		private $id;
		private $firstName;
		private $lastName;
		private $email;
		private $address;
		private $mobile;
		private $position;
		private $updatedBy;
		private $updatedOn;
		private $createdBy;
		private $createdOn;
		private $tableName = 'customers';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setFirstName($firstName) { $this->firstName = $firstName; }
		function getFirstName() { return $this->name; }
		function setLastName($lastName) { $this->lastName = $lastName; }
		function getLastName() { return $this->name; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setAddress($address) { $this->address = $address; }
		function getAddress() { return $this->address; }
		function setMobile($mobile) { $this->mobile = $mobile; }
		function getMobile() { return $this->mobile; }
		function setPosition($position) { $this->position = $position; }
		function getPosition() { return $this->mobile; }
		function setUpdatedBy($updatedBy) { $this->updatedBy = $updatedBy; }
		function getUpdatedBy() { return $this->updatedBy; }
		function setUpdatedOn($updatedOn) { $this->updatedOn = $updatedOn; }
		function getUpdatedOn() { return $this->updatedOn; }
		function setCreatedBy($createdBy) { $this->createdBy = $createdBy; }
		function getCreatedBy() { return $this->createdBy; }
		function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }
		function getCreatedOn() { return $this->createdOn; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllCustomers() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customers;
		}

		public function getCustomerDetailsById() {

			$sql = "SELECT 
						c.*, 
						CONCAT(u.firstName,' ',u.lastName) as created_user,
						CONCAT(u1.firstName,' ',u1.lastName) as updated_user
					FROM customers c 
						JOIN users u ON (c.created_by = u.id) 
						LEFT JOIN users u1 ON (c.updated_by = u1.id) 
					WHERE 
						c.id = :customerId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':customerId', $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;
		}
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, firstName,lastName, email, address, mobile, position, created_by, created_on) VALUES(null, :fistName, :lastName, :email, :address, :mobile, :position, :createdBy, :createdOn)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':fistName', $this->firstName);
			$stmt->bindParam(':lastName', $this->lastName);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':address', $this->address);
			$stmt->bindParam(':mobile', $this->mobile);
			$stmt->bindParam(':position', $this->position);
			$stmt->bindParam(':createdBy', $this->createdBy);
			$stmt->bindParam(':createdOn', $this->createdOn);
			
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

			if( null != $this->getAddress()) {
				$sql .=	" address = '" . $this->getAddress() . "',";
			}

			if( null != $this->getMobile()) {
				$sql .=	" mobile = " . $this->getMobile() . ",";
			}

			$sql .=	" updated_by = :updatedBy, 
					  updated_on = :updatedOn
					WHERE 
						id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->bindParam(':updatedBy', $this->updatedBy);
			$stmt->bindParam(':updatedOn', $this->updatedOn);
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :userId');
			$stmt->bindParam(':userId', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>