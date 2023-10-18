<?php  
	class Customer { 
		private $id;
		private $page;
		private $firstName;
		private $lastName;
		private $username;
		private $company;
		private $gender;
		private $password;
		private $live_secret;
		private $dev_secret;
		private $new_password;
		private $old_password;
		private $active;
		private $updatedBy;
		private $updatedOn;
		private $createdBy;
		private $createdOn;
		private $tableName = 'customers';
		private $tableName2 = 'customer_secrets';
		private $tableName3 = 'company';
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
		function setLiveSecret($live_secret) { $this->live_secret = $live_secret; }
		function getLiveSecret() { return $this->live_secret; }
		function setDevSecret($dev_secret) { $this->dev_secret = $dev_secret; }
		function getDevSecret() { return $this->dev_secret; }
		function setCompany($company) { $this->company = $company; }
		function getCompany() { return $this->company; }
		function setDescription($description) { $this->description = $description; }
		function getDescription() { return $this->description; }
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

		public function getAllCustomers() {
			$stmt = $this->dbConn->prepare("SELECT u.id,u.first_name,u.last_name,u.user_name,u.active,u.created_on,u2.user_name as created_by,u.updated_on,u3.user_name as updated_by FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					LEFT JOIN users u3 ON u.updated_by = u3.id");
			$stmt->execute();
			$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customers;
		}

		public function getCustomerDetailsById() {

			$sql = "SELECT u.id,u.first_name,u.last_name,u.user_name,u.company,g.code,u.gender,u.active,u.created_on,u2.user_name as created_by,u.updated_on,u3.user_name as updated_by FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					INNER JOIN gender g ON u.gender = g.id
					LEFT JOIN users u3 ON u.updated_by = u3.id 
					WHERE u.id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;
		}

		public function getCustomerDetailsByPage() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM customers');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch customers
			
			$sql = "SELECT u.id,u.first_name,u.last_name,u.user_name,c.name as company,g.code,u.gender,u.active,u.created_on,u2.user_name as created_by,u.updated_on,u3.user_name as updated_by FROM users u
					INNER JOIN users u2 ON u.created_by = u2.id
					INNER JOIN gender g ON u.gender = g.id
					INNER JOIN company c ON u.company = c.id
					LEFT JOIN users u3 ON u.updated_by = u3.id
					ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all users for current page
			$customers = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$customer_array = ['currentPage' => $page,'totalPages' => $total_pages,'customers' => $customers];
			return $customer_array;
		}

		public function getCustomerPasswordById() {

			$sql = "SELECT password FROM customers u WHERE u.id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;
		}
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, first_name, last_name, user_name,gender,company, active, password, created_by) VALUES(null, :first_name, :last_name ,:user_name, :gender,:company, :active, :password, :created_by)';
 
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':first_name', $this->firstName);
			$stmt->bindParam(':last_name', $this->lastName);
			$stmt->bindParam(':user_name', $this->username);
			$stmt->bindParam(':gender', $this->gender);
			$stmt->bindParam(':company', $this->company);
			$stmt->bindParam(':active', $this->active);
			$stmt->bindParam(':password', $this->password);
			$stmt->bindParam(':created_by', $this->createdBy);
				
			
			if($stmt->execute()) {
				
				return $this->dbConn->lastInsertId();
			} else {
				return false;
			}
		}

		public function insertSecret() {
			
			$sql = 'INSERT INTO ' . $this->tableName2 . '(customer_id,live_secret,dev_secret, description) VALUES(:customerId,:live_secret ,:dev_secret , :description)';

			$stmt = $this->dbConn->prepare($sql);
			
			$stmt->bindParam(':customerId', $this->id);
			$stmt->bindParam(':live_secret', $this->live_secret);
			$stmt->bindParam(':dev_secret', $this->dev_secret);
			$stmt->bindParam(':description', $this->description);
			
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function insertCompany() {
			
			$sql = 'INSERT INTO ' . $this->tableName3 . '(name,created_by) VALUES(:name,:created_by)';

			$stmt = $this->dbConn->prepare($sql);
			
			$stmt->bindParam(':name', $this->company);
			$stmt->bindParam(':created_by', $this->createdBy);
			
			
			if($stmt->execute()) {
				return $this->dbConn->lastInsertId();
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getFirstName()) {
				$sql .=	" first_name = '" . $this->getFirstName() . "',";
			}

			if( null != $this->getLastName()) {
				$sql .=	" last_name = '" . $this->getLastName() . "',";
			}
			
			if( null != $this->getUserName()) {
				$sql .=	" user_name = '" . $this->getUserName() . "',";
			}

			if( null != $this->getCompany()) {
				$sql .=	" company = '" . $this->getCompany() . "',";
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