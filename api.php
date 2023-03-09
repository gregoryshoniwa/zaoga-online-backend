<?php 

	foreach (glob("Controllers/*.php") as $filename)
	{
		include $filename;
	}
	class Api extends Rest {
		
		public function __construct() {
			parent::__construct();
		}

		use CustomerApis,UserApis,MemberApis,UserTypesApis,DistrictTransactionsApis,AssemblyApis,DistrictApis,DistrictProductsApis;

		public function generateToken() {
			$username = $this->validateParameter('username', $this->param['username'], STRING);
			$password = $this->validateParameter('password', $this->param['password'], STRING);
			try {
				$stmt = $this->dbConn->prepare("SELECT * FROM users WHERE username = :username");
				$stmt->bindParam(":username", $username);
				
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->returnResponse(INVALID_USER_PASS, "Username or Password is incorrect.");
				}

				if($user['active'] == 0 ) {
					$this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
				}
				if(!password_verify($password, $user['password'])) {
					$this->returnResponse(USER_NOT_ACTIVE, "Username or Password is incorrect..");
				}

				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (60*60*24*365*10),
					'user' => $user,
				];

				$token = JWT::encode($paylod, SECRETE_KEY);
				
				$data = ['token' => $token];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
			} catch (Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		

	
	}
	
 ?>