<?php 
	class User {
		private $id;
		private $page;
		private $firstName;
		private $lastName;
		private $username;
		private $authorizations;
		private $gender;
		private $password;
		private $new_password;
		private $old_password;
		private $active;
		private $updatedBy;
		private $updatedOn;
		private $createdBy;
		private $createdOn;
		private $tableName = 'users';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setPage($page) { $this->page = $page; }
		function getPage() { return $this->page; }
		function setFirstName($firstName) { $this->firstName = $firstName;}
		function getFirstName() { return $this->firstName; }
		function setLastName($lastName) { $this->lastName = $lastName; }
		function getLastName() { return $this->lastName; }
		function setUserName($username) { $this->username = $username; }
		function getUserName() { return $this->username; }
		function setGender($gender) { $this->gender = $gender; }
		function getGender() { return $this->gender; }
		function setAuthorizations($authorizations) { $this->authorizations = $authorizations; }
		function getAuthorizations() { return $this->authorizations; }
		function setPassword($password) { $this->password = $password; }
		function getPassword() { return $this->password; }
		function setNewPassword($new_password) { $this->new_password = $new_password; }
		function getNewPassword() { return $this->new_password; }
		function setOldPassword($old_password) { $this->old_password = $old_password; }
		function getOldPassword() { return $this->old_password; }
		function setActive($active) { $this->active = $active; }
		function getActive() { return $this->active; }
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

		public function getAllUsers() {
			$stmt = $this->dbConn->prepare("SELECT u.id,u.firstName,u.lastName,u.username,u.active,u.created_on,u2.username as created_by,u.updated_on,u3.username as updated_by,u.assembly_id FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					LEFT JOIN users u3 ON u.updated_by = u3.id");
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $users;
		}

		public function getUserDetailsById() {

			$sql = "SELECT u.id,u.firstName,u.lastName,u.username,u.authorizations,g.gender_code,u.gender,u.active,u.created_on,u2.username as created_by,u.updated_on,u3.username as updated_by,u.assembly_id FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					INNER JOIN gender g ON u.gender = g.id
					LEFT JOIN users u3 ON u.updated_by = u3.id 
					WHERE u.id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return $user;
		}

		public function getUserDetailsByPage() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM users');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch users
			
			$sql = "SELECT u.id,u.firstName,u.lastName,u.username,u.authorizations,g.gender_code,u.gender,u.active,u.created_on,u2.username as created_by,u.updated_on,u3.username as updated_by,u.assembly_id FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					INNER JOIN gender g ON u.gender = g.id
					LEFT JOIN users u3 ON u.updated_by = u3.id
					ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all users for current page
			$users = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$user_array = ['currentPage' => $page,'totalPages' => $total_pages,'users' => $users];
			return $user_array;
		}

		public function getUserPasswordById() {

			$sql = "SELECT password FROM users u WHERE u.id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return $user;
		}
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, firstName, lastName, username,gender, authorizations, password, created_by) VALUES(null, :firstName, :lastName, :username, :gender, :authorizations, :password, :createdBy)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':firstName', $this->firstName);
			$stmt->bindParam(':lastName', $this->lastName);
			$stmt->bindParam(':username', $this->username);
			$stmt->bindParam(':gender', $this->gender);
			$stmt->bindParam(':authorizations', $this->authorizations);
			$stmt->bindParam(':password', $this->password);
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
			
			if( null != $this->getUserName()) {
				$sql .=	" username = '" . $this->getUserName() . "',";
			}

			if( null != $this->getAuthorizations()) {
				$sql .=	" authorizations = '" . $this->getAuthorizations() . "',";
			}

			if( null != $this->getGender()) {
				$sql .=	" gender = '" . $this->getGender() . "',";
			}


			$sql .=	" updated_by = :updated_by WHERE id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
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

			
			$sql .=	" updated_by = :updated_by WHERE id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->bindParam(':updated_by', $this->updatedBy);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function updatePasswordDefault() {
			
			$sql = "UPDATE $this->tableName SET";
			
			if( null != $this->getPassword()) {
				$sql .=	" password = '" . $this->getPassword() . "',";
			}

			
			$sql .=	" updated_by = :updated_by WHERE id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->bindParam(':updated_by', $this->updatedBy);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function updatePasswordChange() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getNewPassword()) {
				$sql .=	" password = '" . $this->getNewPassword() . "',";
			}

			
			$sql .=	" updated_by = :updated_by WHERE id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->bindParam(':updated_by', $this->id);
			
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