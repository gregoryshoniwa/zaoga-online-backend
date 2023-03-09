<?php 
	class Assembly {
		private $id;
		private $name;
		private $region_id;
		private $district_id;
		private $province_id; 
		private $national_id;
		
		private $tableName = 'assemblies';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name;}
		function getName() { return $this->name; }
		function setRegionId($region_id) { $this->region_id = $region_id; }
		function getRegionId() { return $this->region_id; }
		function setDistrictId($district_id) { $this->district_id = $district_id; }
		function getDistrictId() { return $this->district_id; }
		function setProvinceId($province_id) { $this->province_id = $province_id; }
		function getProvinceId() { return $this->province_id; }
		function setNationalId($national_id) { $this->national_id = $national_id; }
		function getNationalId() { return $this->national_id; }
		
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllAssemblies() {
			$stmt = $this->dbConn->prepare("SELECT * FROM assemblies");
			$stmt->execute();
			$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $members;
		}

		public function getAllAssembliesById() {

			$sql = "SELECT * FROM assemblies a WHERE a.id = :assembly_Id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':assembly_Id', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getAllAssembliesByRegion() {

			$sql = "SELECT * FROM assemblies a WHERE a.region_id = :region_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':region_id', $this->region_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getAllAssembliesByDistrict() {

			$sql = "SELECT * FROM assemblies a WHERE a.district_id = :district_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':district_id', $this->district_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getAllAssembliesByProvince() {

			$sql = "SELECT * FROM assemblies a WHERE a.province_id = :province_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':province_id', $this->province_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getAllAssembliesByNation() {

			$sql = "SELECT * FROM assemblies a WHERE a.national_id = :national_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':national_id', $this->national_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

			

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, name, region_id, district_id,province_id,national_id) VALUES(null, :name, :region_id, :district_id,:province_id, :national_id)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':region_id', $this->region_id);
			$stmt->bindParam(':district_id', $this->district_id);
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
				$sql .=	" region_id = '" . $this->getRegionId() . "',";
			}

			if( null != $this->getSymbol()) {
				$sql .=	" district_id = '" . $this->getDistrictId() . "',";
			}

			if( null != $this->getCode()) {
				$sql .=	" province_id = '" . $this->getProvinceId() . "',";
			}

			if( null != $this->getSymbol()) {
				$sql .=	" national_id = '" . $this->getNationalId() . "',";
			}
		
			$sql .=	" WHERE id = :assembly_Id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':assembly_Id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}


		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :assembly_Id');
			$stmt->bindParam(':assembly_Id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>