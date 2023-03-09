<?php 
	class Currency {
		private $id;
		private $name;
		private $code;
		private $symbol;
		
		private $tableName = 'currencies';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name;}
		function getName() { return $this->name; }
		function setCode($code) { $this->code = $code; }
		function getCode() { return $this->code; }
		function setSymbol($symbol) { $this->symbol = $symbol; }
		function getSymbol() { return $this->symbol; }
		
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllCurrencies() {
			$stmt = $this->dbConn->prepare("SELECT * FROM currencies");
			$stmt->execute();
			$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $members;
		}

		public function getCurrencyDetailsById() {

			$sql = "SELECT * FROM currencies c WHERE c.id = :currency_id";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':currency_id', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

			

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, name, code, symbol) VALUES(null, :name, :code, :symbol)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':code', $this->code);
			$stmt->bindParam(':symbol', $this->symbol);
				
			
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
				$sql .=	" code = '" . $this->getCode() . "',";
			}

			if( null != $this->getSymbol()) {
				$sql .=	" symbol = '" . $this->getSymbol() . "',";
			}
		
			$sql .=	" WHERE id = :currency_id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':currency_id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}


		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :currency_id');
			$stmt->bindParam(':currency_id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>