<?php 

	foreach (glob("Controllers/*.php") as $filename)
	{
		include $filename;
	}
	include "rest.php";
	class Api extends Rest {
		
		public function __construct() {
			parent::__construct();
		}

		use CustomerApis,
			UserApis,
			CurrencyApis,
			MemberApis,
			UserTypesApis,
			DistrictTransactionsApis,
			AssemblyApis,
			DistrictApis,
			ProductsApis;

		public function generateToken() {
			$username = $this->validateParameter('user_name', $this->param['user_name'], STRING,true);
			$password = $this->validateParameter('password', $this->param['password'], STRING,true);
			$secret = null;
			if(isset($this->param['secret'])){
				$secret = $this->param['secret'];
			}
			$type = $this->validateParameter('type', $this->param['type'], STRING,true);
			
			try {
				$stmt = null;
				if($type == "user"){
					$stmt = $this->dbConn->prepare("SELECT u.id,u.user_name,u.password,u.first_name,u.last_name,c.name AS company_name,g.name AS gender,active FROM users u 
					INNER JOIN company c ON c.id = u.company
					INNER JOIN gender g ON g.id = u.gender
					INNER JOIN status s ON s.id = u.active
					WHERE user_name = :user_name");
				}
				if($type == "customer"){
					$stmt = $this->dbConn->prepare("SELECT u.id,u.user_name,u.password,u.first_name,u.last_name,c.name AS company_name,g.name AS gender,active FROM customers u 
					INNER JOIN company c ON c.id = u.company
					INNER JOIN gender g ON g.id = u.gender
					INNER JOIN status s ON s.id = u.active
					WHERE user_name = :user_name");
				}
				if($type == "dev_integration"){
					$stmt = $this->dbConn->prepare("SELECT u.id,u.user_name,u.password,u.first_name,u.last_name,c.name AS company_name,g.name AS gender,active,cs.live_secret,cs.dev_secret FROM customers u 
					INNER JOIN company c ON c.id = u.company
					INNER JOIN gender g ON g.id = u.gender
					INNER JOIN status s ON s.id = u.active
					INNER JOIN customer_secrets cs ON cs.customer_id = u.id
					WHERE u.user_name = :user_name AND cs.dev_secret = :secret");
				}
				if($type == "live_integration"){
					$stmt = $this->dbConn->prepare("SELECT u.id,u.user_name,u.password,u.first_name,u.last_name,c.name AS company_name,g.name AS gender,active,cs.live_secret,cs.dev_secret FROM customers u 
					INNER JOIN company c ON c.id = u.company
					INNER JOIN gender g ON g.id = u.gender
					INNER JOIN status s ON s.id = u.active
					INNER JOIN customer_secrets cs ON cs.customer_id = u.id 
					WHERE u.user_name = :user_name AND cs.live_secret = :secret");
				}
				
				$stmt->bindParam(":user_name", $username);
				if($secret != null){
					$stmt->bindParam(":secret", $secret);
				}
				
				
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->returnResponse(INVALID_USER_PASS, "Username does not exit, please check and try again.");
				}

				if($user['active'] == 0 ) {
					$this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
				}

				if($type == "dev_integration"){
					if(!password_verify($password, $user['password'])) {
						$this->returnResponse(USER_NOT_ACTIVE, "Invalid integration secrect, please check and try again..");
					}
	
					$user_data = array(
						'id' => $user['id'],
						'user_name' => $user['user_name'],
						'first_name' => $user['first_name'],
						'last_name' => $user['last_name'],
						'company_name' => $user['company_name'],
						'gender' => $user['gender'],
						'active' => $user['active']
					);
	
					$paylod = [
						'iat' => time(),
						'iss' => 'norah.co.zw',
						'exp' => time() + (60*5),
						'user' => $user_data,
					];
	
					$token = JWT::encode($paylod, $user['dev_secret']);
					
					$data = ['token' => $token,'exp' => date('Y-m-d H:i:s', time() + (60*5))];
					$this->returnResponse(SUCCESS_RESPONSE, $data);
				}if($type == "live_integration"){
					if(!password_verify($password, $user['password'])) {
						$this->returnResponse(USER_NOT_ACTIVE, "Invalid integration secrect, please check and try again..");
					}
	
					$user_data = array(
						'id' => $user['id'],
						'user_name' => $user['user_name'],
						'first_name' => $user['first_name'],
						'last_name' => $user['last_name'],
						'company_name' => $user['company_name'],
						'gender' => $user['gender'],
						'active' => $user['active']
					);
	
					$paylod = [
						'iat' => time(),
						'iss' => 'norah.co.zw',
						'exp' => time() + (60*5),
						'user' => $user_data,
					];
	
					$token = JWT::encode($paylod, $user['live_secret']);
					
					$data = ['token' => $token,'exp' => date('Y-m-d H:i:s', time() + (60*5))];
					$this->returnResponse(SUCCESS_RESPONSE, $data);
				}else{
					if(!password_verify($password, $user['password'])) {
						$this->returnResponse(USER_NOT_ACTIVE, "Username or Password is incorrect..");
					}
	
					$user_data = array(
						'id' => $user['id'],
						'user_name' => $user['user_name'],
						'first_name' => $user['first_name'],
						'last_name' => $user['last_name'],
						'company_name' => $user['company_name'],
						'gender' => $user['gender'],
						'active' => $user['active']
					);
	
					$paylod = [
						'iat' => time(),
						'iss' => 'norah.co.zw',
						'exp' => time() + (60*60*24*365*10),
						'user' => $user_data,
					];
	
					$token = JWT::encode($paylod, SECRETE_KEY);
					
					$data = ['token' => $token,'exp' => date('Y-m-d H:i:s', time() + (60*60*24*365*10))];
					$this->returnResponse(SUCCESS_RESPONSE, $data);
				}
				
			} catch (Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		

	
	}
	
 ?>