<?php 
	class UserType {
		private $id;
		private $page;
		private $user_type;
		
		private $tableName = 'user_type';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setPage($page) { $this->page = $page; }
		function getPage() { return $this->page; }
		function setUserType($user_type) { $this->user_type = $user_type;}
		function getUserType() { return $this->user_type; }
		

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		

		public function getMemberDetailsById() {
 
			$sql = "SELECT * FROM user_types WHERE m.id = :memberId";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':user_type_id', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getUserTypeDetailsByPage() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM user_types');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch users
			
			$sql = "SELECT id as value,user_type as label from user_types ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all users for current page
			$users = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$user_array = ['currentPage' => $page,'totalPages' => $total_pages,'user_types' => $users];
			return $user_array;
		}

		
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, user_type) VALUES(null, :user_type)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':user_type', $this->user_type);
			
				
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getUserType()) {
				$sql .=	" user_type = '" . $this->getUserType() . "',";
			}

			$sql .=	" WHERE id = :user_type_id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':user_type_id', $this->id);
			$stmt->bindParam(':user_type', $this->user_type);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :user_type_id');
			$stmt->bindParam(':user_type_id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>