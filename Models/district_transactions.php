<?php 
	class DistrictTransactions {
		private $id;
		private $user_id;
		private $member_id;
		private $district_id;
		private $assembly_id;
		private $district_product_id;
		private $currency;
		private $amount;
		private $form_id;
		private $createdOn;
		private $recievedBy;
		private $receivedOn;
		private $tableName = 'district_transactions';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setUserId($user_id) { $this->user_id = $user_id; }
		function getUserId() { return $this->user_id; }
		function setMemberId($member_id) { $this->member_id = $member_id;}
		function getMemberId() { return $this->member_id; }
		function setDistrictId($district_id) { $this->district_id = $district_id; }
		function getDistrictId() { return $this->district_id; }
		function setDistrictProductId($district_product_id) { $this->district_product_id = $district_product_id; }
		function getDistrictProductId() { return $this->district_product_id; }
		function setCurrency($currency) { $this->currency = $currency; }
		function getCurrency() { return $this->currency; }
		function setAssemblyId($assembly_id) { $this->assembly_id = $assembly_id; }
		function getAssemblyId() { return $this->assembly_id; }
		function setAmount($amount) { $this->amount = $amount; }
		function getAmount() { return $this->amount; }
		function setFormId($form_id) { $this->form_id = $form_id; }
		function getFormId() { return $this->form_id; }
		function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }
		function getCreatedOn() { return $this->createdOn; }
		function setRecievedOn($receivedOn) { $this->receivedOn = $receivedOn; }
		function getRecievedOn() { return $this->receivedOn; }
		function setRecievedBy($recievedBy) { $this->recievedBy = $recievedBy; }
		function getRecievedBy() { return $this->recievedBy; }
		

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		
		public function getDistrictTransactionsById() {
 
			$sql = "SELECT d.id,u.username AS assembly_secretary,CONCAT((SELECT p.position FROM positions p WHERE id = m.position),' : ', m.firstName,' ',m.lastName) AS member,dd.name AS district,
					r.name AS region,a.name AS assembly,dp.name AS district_product,c.code AS currency,d.amount,d.form_id,
					ru.username AS office_secretary,d.created_on,d.recieved_on
					FROM district_transactions d
					INNER JOIN assembles a ON d.assembly_id = a.id
					INNER JOIN members m ON d.member_id = m.id
					INNER JOIN users u ON d.user_id = u.id
					LEFT JOIN users ru ON d.recieved_by = ru.id
					INNER JOIN districts dd ON d.district_id = dd.id 
					INNER JOIN district_products dp ON d.district_product_id = dp.id
					INNER JOIN currencies c ON d.currency = c.id
					INNER JOIN regions r ON d.regional_id = r.id
					WHERE d.form_id = :formId";
			
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':formId', $this->form_id);
			$stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			return $member;
		}

		public function getDistrictTransactionsByPage() {

			// Calculate Total pages
			$perPage = 7;
			$stmt = $this->dbConn->query('SELECT count(*) FROM members');
			$total_results = $stmt->fetchColumn();
			$total_pages = ceil($total_results / $perPage);
			
			// Current page
			$page = $this->page;
			$starting_limit = ($page - 1) * $perPage;
			
			// Query to fetch users
			
			$sql = "SELECT d.id,u.username AS assembly_secretary,CONCAT((SELECT p.position FROM positions p WHERE id = m.position),' : ', m.firstName,' ',m.lastName) AS member,dd.name AS district,
					r.name AS region,a.name AS assembly,dp.name AS district_product,c.code AS currency,d.amount,d.form_id,
					ru.username AS office_secretary,d.created_on,d.recieved_on
					FROM district_transactions d
					INNER JOIN assembles a ON d.assembly_id = a.id
					INNER JOIN members m ON d.member_id = m.id
					INNER JOIN users u ON d.user_id = u.id
					LEFT JOIN users ru ON d.recieved_by = ru.id
					INNER JOIN districts dd ON d.district_id = dd.id 
					INNER JOIN district_products dp ON d.district_product_id = dp.id
					INNER JOIN currencies c ON d.currency = c.id
					INNER JOIN regions r ON d.regional_id = r.id
					ORDER BY id DESC LIMIT $starting_limit,$perPage";
		
			// Fetch all users for current page
			$users = $this->dbConn->query($sql)->fetchAll(PDO::FETCH_ASSOC);;
			$user_array = ['currentPage' => $page,'totalPages' => $total_pages,'members' => $users];
			return $user_array;
		}

		
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, user_id, member_id, district_product_id, currency,assembly_id, amount,form_id) VALUES(null, :user_id, :member_id, :district_product_id,:currency,:assembly_id, :amount, :form_id)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':user_id', $this->user_id);
			$stmt->bindParam(':member_id', $this->member_id);
			$stmt->bindParam(':district_product_id', $this->district_product_id);
			$stmt->bindParam(':currency', $this->currency);
			$stmt->bindParam(':assembly_id', $this->assembly_id);
			$stmt->bindParam(':amount', $this->amount);
			$stmt->bindParam(':form_id', $this->form_id);
				
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		

		public function updateDistrictTransaction() {
			
			$sql = "UPDATE $this->tableName SET";
						
			$sql .=	" recieved_by = :recieved_by WHERE form_id = :form_id";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':form_id', $this->form_id);
			$stmt->bindParam(':recieved_by', $this->recievedBy);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE form_id = :form_id');
			$stmt->bindParam(':form_id', $this->form_id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>