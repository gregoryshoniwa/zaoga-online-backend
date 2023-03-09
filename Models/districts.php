<?php 
	class District {
		private $id;
		private $name;
		private $province_id; 
		private $national_id;
		
		private $tableName = 'districts';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name;}
		function getName() { return $this->name; }
		function setProvinceId($province_id) { $this->province_id = $province_id; }
		function getProvinceId() { return $this->province_id; }
		function setNationalId($national_id) { $this->national_id = $national_id; }
		function getNationalId() { return $this->national_id; }
		
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllDistricts() {
			$stmt = $this->dbConn->prepare("SELECT * FROM districts");
			$stmt->execute();
			$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $members;
		}

		public function getAllDistrictsById() {

			$sql = "SELECT * FROM districts a WHERE a.id = :district_Id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':district_Id', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		


		public function getAllDistrictsByProvince() {

			$sql = "SELECT * FROM districts a WHERE a.province_id = :province_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':province_id', $this->province_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getAllDistrictsByNation() {

			$sql = "SELECT * FROM districts a WHERE a.national_id = :national_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':national_id', $this->national_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

			

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, name,province_id,national_id) VALUES(null, :name,:province_id, :national_id)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':province_id', $this->province_id);
			$stmt->bindParam(':national_id', $this->national_id);
					
			
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

			if( null != $this->getCode()) {
				$sql .=	" province_id = '" . $this->getProvinceId() . "',";
			}

			if( null != $this->getSymbol()) {
				$sql .=	" national_id = '" . $this->getNationalId() . "',";
			}
		
			$sql .=	" WHERE id = :district_Id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':district_Id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}


		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :district_Id');
			$stmt->bindParam(':district_Id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>