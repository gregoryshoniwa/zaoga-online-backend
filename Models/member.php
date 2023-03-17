<?php 
	class Member {
		private $id;
		private $page;
		private $firstName;
		private $lastName;
		private $gender;
		private $active;
		private $national_id;
		private $assembly_id;
		private $position;
		private $updatedBy;
		private $updatedOn;
		private $createdBy;
		private $createdOn;
		private $tableName = 'members';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setPage($page) { $this->page = $page; }
		function getPage() { return $this->page; }
		function setFirstName($firstName) { $this->firstName = $firstName;}
		function getFirstName() { return $this->firstName; }
		function setLastName($lastName) { $this->lastName = $lastName; }
		function getLastName() { return $this->lastName; }
		function setGender($gender) { $this->gender = $gender; }
		function getGender() { return $this->gender; }
		function setActive($active) { $this->active = $active; }
		function getActive() { return $this->active; }
		function setNationalId($national_id) { $this->national_id = $national_id; }
		function getNationalId() { return $this->national_id; }
		function setAssemblyId($assembly_id) { $this->assembly_id = $assembly_id; }
		function getAssemblyId() { return $this->assembly_id; }
		function setPosition($position) { $this->position = $position; }
		function getPosition() { return $this->position; }
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

		public function getAllMembers() {
			$stmt = $this->dbConn->prepare("SELECT m.id,m.firstName,m.lastName,m.national_id,a.name AS assembly_name,p.position,m.created_on,u.username FROM members m
					INNER JOIN assemblies a ON m.assembly_id = a.id
					INNER JOIN positions p ON m.position = p.id
					INNER JOIN users u ON m.created_by = u.id");
			$stmt->execute();
			$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $members;
		}

		public function getMemberDetailsById() {
 
			$sql = "SELECT m.id,m.firstName,m.lastName,g.gender_code,m.gender,m.position as position_id,m.active,m.national_id,a.name AS assembly_name,p.position,m.created_on,u.username as created_by,m.updated_on,u2.username as updated_by FROM members m
					LEFT JOIN assemblies a ON m.assembly_id = a.id
					INNER JOIN positions p ON m.position = p.id
					INNER JOIN users u ON m.created_by = u.id
					INNER JOIN gender g ON m.gender = g.id
					LEFT JOIN users u2 ON m.updated_by = u2.id
					WHERE m.id = :memberId";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':memberId', $this->id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getMemberDetailsByPage() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM members');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch users
			
			$sql = "SELECT m.id,m.firstName,m.lastName,g.gender_code,m.gender,m.position as position_id,m.active,m.national_id,a.name AS assembly_name,p.position,m.created_on,u.username as created_by,m.updated_on,u2.username as updated_by FROM members m
					LEFT JOIN assemblies a ON m.assembly_id = a.id
					INNER JOIN positions p ON m.position = p.id
					INNER JOIN users u ON m.created_by = u.id
					INNER JOIN gender g ON m.gender = g.id
					LEFT JOIN users u2 ON m.updated_by = u2.id
					ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all users for current page
			$users = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$user_array = ['currentPage' => $page,'totalPages' => $total_pages,'members' => $users];
			return $user_array;
		}

		
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, firstName, lastName, gender, national_id, position,assembly_id, created_by) VALUES(null, :firstName, :lastName, :gender,:national_id,:position,:assembly_id, :createdBy)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':firstName', $this->firstName);
			$stmt->bindParam(':lastName', $this->lastName);
			$stmt->bindParam(':gender', $this->gender);
			$stmt->bindParam(':national_id', $this->national_id);
			$stmt->bindParam(':position', $this->position);
			$stmt->bindParam(':assembly_id', $this->assembly_id);
			$stmt->bindParam(':createdBy', $this->createdBy);
				
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getFirstName()) {
				$sql .=	" firstName = '" . $this->getFirstName() . "',";
			}

			if( null != $this->getLastName()) {
				$sql .=	" lastName = '" . $this->getLastName() . "',";
			}

			if( null != $this->getGender()) {
				$sql .=	" gender = '" . $this->getGender() . "',";
			}

			if( null != $this->getNationalId()) {
				$sql .=	" national_id = '" . $this->getNationalId() . "',";
			}
			if( null != $this->getPosition()) {
				$sql .=	" position = '" . $this->getPosition() . "',";
			}

			// if( null != $this->getAssemblyId()) {
			// 	$sql .=	" assembly_id = '" . $this->getAssemblyId() . "',";
			// }

			$sql .=	" updated_by = :updated_by WHERE id = :memberId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':memberId', $this->id);
			$stmt->bindParam(':updated_by', $this->updatedBy);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function updateActive() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getActive()) {
				$sql .=	" active = '" . $this->getActive() . "',";
			}

			
			$sql .=	" updated_by = :updated_by WHERE id = :memberId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':memberId', $this->id);
			$stmt->bindParam(':updated_by', $this->updatedBy);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :memberId');
			$stmt->bindParam(':memberId', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>