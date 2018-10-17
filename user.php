<?php

class User {
	public $id;
	public $url;
	public $title;
	public $content;


	public static function  find_all_users() {
	global $db;
	$query = "SELECT * FROM to_index ";
	$result = $db->perform_query($query);
	return $result;
	}

	public static function find_user_by_id($id=0) {
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM users WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_user_id($id=0) {
		global $db;
		$result_array = self::find_by_sql("SELECT id FROM users WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function add_link($url) {
		global $db;
		$safeurl = trim($db->string_prep($url));
		if (!self::verify_link_exist($url, "to_index")) {
			$query  = "INSERT INTO to_index (url) VALUES ('{$safeurl}')";
			$result = $db->perform_query($query);
			return true;
		}else {
			return false;
		}
	}

	public static function add_link_to_real($title, $url, $page) {
		global $db;
		$safetitle = trim($db->string_prep($title));
		$safeurl = trim($db->string_prep($url));
		$safecontent = trim($db->string_prep($page));
		if (!self::verify_link_exist($url, "indexed")) {
			$query  = "INSERT INTO indexed (title, url, content) VALUES ('{$safetitle}', '{$safeurl}', '{$safecontent}')";
			$result = $db->perform_query($query);
			return true;
		}else {
			return false;
		}
	}

	public static function verify_link_exist($url, $table) {
		global $db;
		$result_array = self::find_by_sql("SELECT url FROM {$table} WHERE url='{$url}'");
		return !empty($result_array) ? true : false;
	}

	// public static function find_user_profile($user_id="") {
	// 	global $db;
	// 	$sanitized_user_id = $db->string_prep($user_id);
	// 	$result_array = self::find_by_sql("SELECT * FROM user_profiles WHERE user_id='$sanitized_user_id' LIMIT 1");
	// 	return !empty($result_array) ? array_shift($result_array) : false;
	// }

	public static function find_by_sql($sql="") {
		global $db;
		$result = $db->perform_query($sql);
		$object_array =array();
		 while ($row = $db->fetch_array($result)) {
		 	$object_array[] = self::instantiate($row);
		 }
		return $object_array;
	}

	// public static function validate_user($user="") {
	// 	global $db;
	// 	$result_array = self::find_by_sql("SELECT id FROM users WHERE id={$user}");
	// 	return !empty($result_array) ? true : false;
	// }

	// public static function authenticate_user($username="", $password="") {
	// 	global $db;
	// 	$sql_query  = "SELECT user_pass FROM users ";
	// 	$sql_query .= "WHERE user_login='$username' ";
	// 	$result = $db->perform_query($sql_query);
	// 	$got_user = $db->fetch_array($result);
	// 	if (crypt($password, $got_user['user_pass'])==$got_user['user_pass']) {
	// 		$sql  = "SELECT * FROM users WHERE ";
	// 		$sql .= "user_login='$username' AND ";
	// 		$sql .= "user_pass = '{$got_user['user_pass']}' AND ";
	// 		$sql .= "account_status='confirmed' OR ";
	// 		$sql .= "account_status='confirm_pending' AND ";
	// 		$sql .= "user_status='active' ";
	// 		$sql .= "LIMIT 1";
	// 		$result_array = self::find_by_sql($sql);
	// 		return !empty($result_array) ? array_shift($result_array) : false;
	// 	}		
	// }

	// public static function authenticate_user_for_login_on_signup($username="", $password="") {
	// 	global $db;
	// 	$sql_query  = "SELECT user_pass FROM users ";
	// 	$sql_query .= "WHERE user_login='$username' ";
	// 	$result = $db->perform_query($sql_query);
	// 	$got_user = $db->fetch_array($result);
	// 	if (crypt($password, $got_user['user_pass'])==$got_user['user_pass']) {
	// 		$sql  = "SELECT * FROM users WHERE ";
	// 		$sql .= "user_login='$username' AND ";
	// 		$sql .= "user_pass = '{$got_user['user_pass']}' ";
	// 		$sql .= "LIMIT 1";
	// 		$result_array = self::find_by_sql($sql);
	// 		return !empty($result_array) ? array_shift($result_array) : false;
	// 	}		
	// }

	// public function full_name($user_id="") {
	// 	global $db;
	// 	$sanitized_user_id = $db->string_prep($user_id);
	// 	$query = "SELECT first_name, last_name FROM user_profiles WHERE user_id='$sanitized_user_id' LIMIT 1";
	// 	$result = $db->perform_query($query);
	// 	$array = $db->fetch_array($result);
	// 	if (!empty($array)) {
	// 		$string = implode(" ", $array);
	// 		return ucwords($string);
	// 	} else {
	// 		return null;
	// 	}		
	// }

	// public static function first_name($user_id="") {
	// 	global $db;
	// 	$sanitized_user_id = $db->string_prep($user_id);
	// 	$query = "SELECT first_name FROM user_profiles WHERE user_id='$sanitized_user_id' LIMIT 1";
	// 	$result = $db->perform_query($query);
	// 	$array = $db->fetch_array($result);
	// 	if (!empty($array)) {
	// 		$string = implode("", $array);
	// 		return ucwords($string);
	// 	} else {
	// 		return null;
	// 	}		
	// }

	// public function edit_profile() {
	// 	global $db;
	// 	$user_id = $db->string_prep($this->user_id);
	// 	$first_name = $db->string_prep($this->first_name);
	// 	$last_name = $db->string_prep($this->last_name);
	// 	$gender = $db->string_prep($this->gender);
	// 	$email = $db->string_prep($this->email);
	// 	$website = $db->string_prep($this->website);
	// 	$city = $db->string_prep($this->city);
	// 	$country = $db->string_prep($this->country);
	// 	$about_yourself = $db->string_prep($this->about_yourself);

	// 	$query = "UPDATE user_profiles SET ";
	// 	$query .= "first_name='$first_name'"; 
	// 	$query .= ", last_name='$last_name'";
	// 	$query .= ", gender='$gender'";
	// 	$query .= ", email='$email'";
	// 	$query .= ", website='$website'";
	// 	$query .= ", city='$city'";
	// 	$query .= ", country='$country'";
	// 	$query .= ", about_yourself='$about_yourself'";
	// 	$query .= " WHERE user_id='$user_id'";
	// 	$result = $db->perform_query($query);
	// 	if ($result) {
	// 		return true;
	// 	}
	// 	else {
	// 		return false;
	// 	}
	// }

	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = $value;				
			}
		}
		return $object;
	}

 	private function has_attribute($attribute) {
 		$object_vars = get_object_vars($this);
 		return array_key_exists($attribute, $object_vars);
 	}
 	
}

// class CreateUser extends User {
// 	public function signupUser() {
// 		global $db;
// 		$small_username = strtolower($this->user_login);
// 		$hashed_password = self::password_encrypt($this->user_pass);
// 		$query  = "INSERT INTO users (user_login, user_pass, user_date_time, user_status, account_status, user_regd_ip) ";
// 		$query .= "VALUES ('$small_username', '$hashed_password', '$this->user_date_time', '$this->user_status', '$this->account_status', '$this->user_regd_ip') ";
// 		$result = $db->perform_query($query);
// 	}

// 	public function update_profile($user_id) {
// 		global $db;
// 		$sanitized_user_id = $db->string_prep($user_id);
// 		$query  = "INSERT INTO user_profiles (user_id, first_name, last_name, gender, email, email_type) ";
// 		$query .= "VALUES ('$sanitized_user_id', '$this->first_name', '$this->last_name', '$this->gender', '$this->email', '$this->email_type') ";
// 		$result = $db->perform_query($query);
// 		if (!empty($user_id) && $result) {
// 			return true;
// 		} else {
// 			return false;			
// 		}
// 	}	

// 	public static function password_encrypt($password) {
//   	  $hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
// 	  $salt_length = 22; 					// Blowfish salts should be 22-characters or more
// 	  $salt = self::generate_salt($salt_length);
// 	  $format_and_salt = $hash_format . $salt;
// 	  $hash = crypt($password, $format_and_salt);
// 		return $hash;
// 	}
	
// 	private static function generate_salt($length) {
// 	  // Not 100% unique, not 100% random, but good enough for a salt
// 	  // MD5 returns 32 characters
// 	  $unique_random_string = md5(uniqid(mt_rand(), true));
	  
// 		// Valid characters for a salt are [a-zA-Z0-9./]
// 	  $base64_string = base64_encode($unique_random_string);
	  
// 		// But not '+' which is valid in base64 encoding
// 	  $modified_base64_string = str_replace('+', '.', $base64_string);
	  
// 		// Truncate string to the correct length
// 	  $salt = substr($modified_base64_string, 0, $length);
	  
// 		return $salt;
// 	}

// 	public function authenticate_username() {
// 		global $db;
// 		$small_username = strtolower($this->user_login);
// 		$query  = "SELECT user_login FROM users WHERE user_login='$small_username'";
// 		$result = $db->perform_query($query);
// 		$count = mysqli_num_rows($result);
// 		if($count > 0){
// 		    return false;
// 		}
// 		else {
// 		    return true;
// 		}
// 	}
// }
?>