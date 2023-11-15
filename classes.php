<?php 
/* User */
class User {
	public $status;
	public $message = array();
	protected $table_name = "users";

	public function save($data) {
       $database = new Database();

       $username = $database->escapeString($data['username']);
       $email = $database->escapeString($data['email']);
       $password = $database->escapeString($data['password']);
       $confirm_pass = $database->escapeString($data['confirm_pass']);
       $first_name = $database->escapeString($data['first_name']);
       $last_name = $database->escapeString($data['last_name']);
       $middle_name = $database->escapeString($data['middle_name']);
       $age = $database->escapeString($data['age']);
       $about_yourself = $database->escapeString($data['about_yourself']);
       $user_salt = $this->generateUserSalt();

       if($password == $confirm_pass) {
       	$password = md5($password).$user_salt;
        $query = "INSERT INTO `".$this->table_name."`(username, email, password,first_name,last_name,middle_name,age,about_yourself,user_salt) VALUES('".$username."','".$email."','".$password."','".$first_name."','".$last_name."','".$middle_name."','".$age."','".$about_yourself."','".$user_salt."')";
        $database->emteDirectQuery($query,'insert');

        if($database->last_insert_id) {

        }
        
        return $database->last_insert_id;

       } else {
       	 return false;
       }
	}

	public function generateUserSalt($length = 20) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[random_int(0, $charactersLength - 1)];
	    }

	    return $randomString;
	}

	public function setSession($user_id) {
		$user_id = intval($user_id);
		$database = new Database();

		$query = "SELECT * FROM `".$this->table_name."` WHERE user_id = $user_id";
		$results = $database->emteDirectQuery($query, 'select');

		foreach($results as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}

	public function login($data) {
	    $database = new Database();

	    $username = $database->escapeString($data['username']);
	    $password = $database->escapeString($data['password']);
	    $user_salt = $this->getUserSalt($username);
	    $password = md5($password).$user_salt;
        
        $query = "SELECT * FROM `users` WHERE username = '".$username."' AND password = '".$password."'";
        $results = $database->emteDirectQuery($query, 'select');
        
        if($results) {
            $_SESSION['account_created'] = '';
        	foreach($results as $r) {
	        	return $r;
	        }
        }
	}

	public function getUserSalt($username) {
		$database = new Database();

		$query = "SELECT * FROM `users` WHERE username = '".$username."'";
		$result = $database->emteDirectQuery($query, 'select');

		if($result) {
			return $result[0]['user_salt'];
		} else {
			return false;
		}
	}

	public function update($data) {
		$database = new Database();
        
        $user_id = $database->escapeString($data['user_id']);
        $username = $database->escapeString($data['username']);
        $email = $database->escapeString($data['email']);
        if(isset($data['new_password']) && isset($data['old_password'])) {
        	if(!$this->checkOldPassword($data)){
        		$this->message[] = 'old password is correct';
            }
        }
        $password = $database->escapeString($data['new_password']);
        $user_salt = $this->generateUserSalt();
        $password = md5($password).$user_salt;
        $first_name = $database->escapeString($data['first_name']);
        $middle_name = $database->escapeString($data['middle_name']);
        $last_name = $database->escapeString($data['last_name']);
        $phone = $database->escapeString($data['phone']);
        $age = $database->escapeString($data['age']);
        $about_yourself = $database->escapeString($data['about_yourself']);
        
        if($_FILES["profile"]["name"]) {
        	if($data['old_password'] && $data['new_password']) {
        		$profile = $this->uploadProfile($_FILES);
	            $query = "UPDATE `".$this->table_name."` SET username = '".$username."', email = '".$email."', password = '".$password."', first_name = '".$first_name."', last_name = '".$last_name."',middle_name = '".$middle_name."',age = '".$age."',about_yourself = '".$about_yourself."',phone = '".$phone."', profile = '".$profile."', user_salt = '".$user_salt."' WHERE user_id = $user_id";
	            $result = $database->emteDirectQuery($query, 'update');
        	} else {
        		$profile = $this->uploadProfile($_FILES);
	            $query = "UPDATE `".$this->table_name."` SET username = '".$username."', email = '".$email."', first_name = '".$first_name."', last_name = '".$last_name."',middle_name = '".$middle_name."',age = '".$age."',about_yourself = '".$about_yourself."',phone = '".$phone."', profile = '".$profile."' WHERE user_id = $user_id";
	            $result = $database->emteDirectQuery($query, 'update');
        	}
            if($result) {
            	$this->message['success'] = "Update successful.";
            } else {
            	$this->message['error'][] = "Unable to update profile.";
            }
        } else {
            if($data['old_password'] && $data['new_password']) {
            	$query = "UPDATE `".$this->table_name."` SET username = '".$username."', email = '".$email."', password = '".$password."', first_name = '".$first_name."', last_name = '".$last_name."',middle_name = '".$middle_name."',age = '".$age."',about_yourself = '".$about_yourself."',phone = '".$phone."', user_salt = '".$user_salt."' WHERE user_id = $user_id";
                $result = $database->emteDirectQuery($query, 'update');
            } else {
                $query = "UPDATE `".$this->table_name."` SET username = '".$username."', email = '".$email."', first_name = '".$first_name."', last_name = '".$last_name."',middle_name = '".$middle_name."',age = '".$age."',about_yourself = '".$about_yourself."',phone = '".$phone."' WHERE user_id = $user_id";
                $result = $database->emteDirectQuery($query, 'update');
            }        
        }

        return $this->message;

	}

	public function checkOldPassword($data) {
		$database = new Database();
        
        $password = $database->escapeString($data['old_password']);
        $username = $database->escapeString($data['username']);
        $user_salt = $this->getUserSalt($username);

        $password = md5($password).$user_salt;

        $query = "SELECT * FROM `".$this->table_name."` WHERE password = '".$password."'";
        $result = $database->emteDirectQuery($query, 'select');

        return $result;
	}

	public function uploadProfile($file) {
		$database = new Database();

		$target_dir = "uploads/profile/";
		$target_file = $target_dir . basename($database->escapeString($_FILES["profile"]["name"]));
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["profile"]["tmp_name"]);
		if($check !== false) {
		   $uploadOk = 1;
		} else {
		   $this->message['error'][] = "File is not an image.";
		   $uploadOk = 0;
		}

		// Check if file already exists
		if (file_exists($target_file)) {
		  $this->message['error'][] =  "Sorry, file already exists.";
		  $uploadOk = 0;
		}


		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		  $this->message['error'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		  $uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		  $this->message['error'][] = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		  if (move_uploaded_file($_FILES["profile"]["tmp_name"], BASE_APP
		  	.$target_file)) {
		    return $target_file;
		  } else {
		    $this->message['error'][] = "Sorry, there was an error uploading your file.";
		  }
		}
	}

	public function getUserData($user_id) {
		$database = new Database();

		$user_id = intval($user_id);
		$query = "SELECT * FROM `".$this->table_name."` WHERE user_id = $user_id";
		$result = $database->emteDirectQuery($query, 'select');

        return $result;
	}

	public function getAllFriends($user_id) {
        $database = new Database();

        $user_id = intval($user_id);
        $query = "SELECT * FROM `friends` WHERE user_id = $user_id";
        $result = $database->emteDirectQuery($query, 'select');

        return $result;
	}

	public function getUserFriends($user_id) {
        $database = new Database();

        $user_id = intval($user_id);
        $query = "SELECT `friends`.*,`friends`.status as 'friend_status',`users`.* FROM `friends`,`users` 
                  WHERE `users`.user_id = `friends`.friend_id AND `friends`.`user_id` = $user_id";
        $result = $database->emteDirectQuery($query, 'select');

        return $result;
	}

	public function getUserConfirmedFriends($user_id) {
        $database = new Database();

        $user_id = intval($user_id);
        $query = "SELECT `friends`.*,`users`.* FROM `friends`,`users` 
                  WHERE `users`.user_id = `friends`.user_id AND `friends`.friend_id = $user_id AND `friends`.status = 1";
        $result = $database->emteDirectQuery($query, 'select');
         
        if($result) {
        	return $result;
        } else {
        	$database = new Database();
        	$query = "SELECT `friends`.*,`users`.* FROM `friends`,`users` 
                  WHERE `users`.user_id = `friends`.user_id AND `friends`.friend_id = $user_id AND `friends`.status = 1";
            $result = $database->emteDirectQuery($query, 'select');

        	return $result;
        }
	}

	public function getAllFriendRequests($user_id) {
        $database = new Database();

        $user_id = intval($user_id);
        $query = "SELECT `friends`.*,`users`.* FROM `friends`,`users` 
                  WHERE `users`.user_id = `friends`.user_id AND `friends`.friend_id = $user_id AND `friends`.status = 0";
        $result = $database->emteDirectQuery($query, 'select');

        return $result;
	}

	public function getAllUsers($user_id) {
		$database = new Database();

		$query = "SELECT `".$this->table_name."`.* FROM `".$this->table_name."` WHERE `".$this->table_name."`.user_id != $user_id AND user_id NOT IN(SELECT friend_id FROM `friends`) OR user_id IN(SELECT friend_id FROM `friends` WHERE status = 0) ORDER BY rand()";
		$result = $database->emteDirectQuery($query, 'select');

		return $result;
	}

	public function addFriend($data) {
		$database = new Database();

		$user_id = intval($data['user_id']);
		$friend_id = intval($data['friend_id']);

		$query = "INSERT INTO `friends`(user_id, friend_id) VALUES($user_id, $friend_id)";
		$result = $database->emteDirectQuery($query, 'insert');

		return $result;
	}

	public function confirmFriend($data) {
		$database = new Database();

		$user_id = intval($data['user_id']);
		$friend_id = intval($data['friend_id']);

		$query = "UPDATE `friends` SET status = 1 WHERE friend_id = $user_id AND user_id = $friend_id";
		$result = $database->emteDirectQuery($query, 'update');

		return $result;
	}

	public function forgotPassword($data) {
		$database = new Database();

		$to = $database->escapeString($data['email']);

		if($this->checkEmail($to)) {
			$subject = "Reset Your Password";

			$message = "
			<html>
			<head>
			<title>Reset Your Password</title>
			</head>
			<body>
			<p>Please use the link below to reset your password.</p>
			<a href='http://localhost/match-made/reset-password.php?email=".$to."'>Reset my password</a>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <matchmade@example.com>' . "\r\n";

			if(mail($to,$subject,$message,$headers)) {
				return 'reset';
			} else {
				return 'email not sent';
			}
		} else {
			return 'error';
		}
	}

    public function checkEmail($email) {
    	$database = new Database();

    	$email = $database->escapeString($email);
    	$query = "SELECT * FROM `".$this->table_name."` WHERE email = '".$email."'";
    	$result = $database->emteDirectQuery($query, 'select');

    	return $result;
    }

    public function resetPassword($data) {
    	$database = new Database();

        $email = $database->escapeString($data['email']);
        $password = $database->escapeString($data['password']);
        $confirm_pass = $database->escapeString($data['confirm_pass']);

        if($password == $confirm_pass && $this->checkEmail($email)) {
        	$user_salt = $this->generateUserSalt();
        	$password = md5($password).$user_salt;
        	$query = "UPDATE `".$this->table_name."` SET password = '".$password."', user_salt = '".$user_salt."' WHERE email = '".$email."'";
        	$result = $database->emteDirectQuery($query, 'update');
        	return $result;
        } else {
        	return 'error';
        }
    }

}
/* End user */

/* Post */
class Post {
	public $user_id;
	public $post;

	protected $table_name = "posts";

	public function save($data) {
		$database = new Database();

        $user_id = intval($data['user_id']);
		$post = $database->escapeString($data['post']);

		$query = "INSERT INTO `".$this->table_name."`(user_id, post) VALUES($user_id, '".$post."')";
		$result = $database->emteDirectQuery($query, 'insert');

		return $database->last_insert_id;
		
	}

	public function getUserPost($post_id) {
		$database = new Database();

		$post_id = intval($post_id);
		$query = "SELECT `posts`.*,`users`.*, (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 1) as 'total_likes', (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 2) as 'total_dislikes', (SELECT COUNT(*) FROM `comments` WHERE `comments`.post_id = `posts`.`post_id` AND `comments`.`user_id` = `users`.`user_id`) as 'total_comments' FROM `".$this->table_name."`,`users` WHERE `users`.user_id = `".$this->table_name."`.user_id AND `".$this->table_name."`.post_id = $post_id";
		$result = $database->emteDirectQuery($query, 'select');

		return $result;
	}

	public function getPosts($user_id) {
		$database = new Database();
		$user_id = intval($user_id);

		$query = "SELECT `posts`.*, (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`status` = 1) as 'total_likes',(SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`status` = 2) as 'total_dislikes', (SELECT COUNT(*) FROM `comments` WHERE `comments`.post_id = `posts`.`post_id`) as 'total_comments' FROM `posts` WHERE `posts`.user_id IN(SELECT friend_id FROM friends WHERE user_id = $user_id AND status = 1) OR posts.user_id = $user_id ORDER BY rand()";
		$results = $database->emteDirectQuery($query, 'select');

		return $results;
	}

	public function getUserPosts($user_id) {
		$database = new Database();

		$query = "SELECT `posts`.*,`users`.*, (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 1) as 'total_likes', (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 2) as 'total_dislikes', (SELECT COUNT(*) FROM `comments` WHERE `comments`.post_id = `posts`.`post_id` AND `comments`.`user_id` = `users`.`user_id`) as 'total_comments' FROM `".$this->table_name."`,`users` WHERE `users`.user_id = `".$this->table_name."`.user_id ORDER BY rand()";
		$results = $database->emteDirectQuery($query, 'select');

		return $results;
	}

	public function getAllUserPosts($user_id) {
		$database = new Database();
        $user_id = intval($user_id);

		$query = "SELECT `posts`.*,`users`.*, (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 1) as 'total_likes', (SELECT COUNT(*) FROM `reactions` WHERE `reactions`.post_id = `posts`.`post_id` AND `reactions`.`user_id` = `users`.`user_id` AND `reactions`.`status` = 2) as 'total_dislikes', (SELECT COUNT(*) FROM `comments` WHERE `comments`.post_id = `posts`.`post_id` AND `comments`.`user_id` = `users`.`user_id`) as 'total_comments' FROM `".$this->table_name."`,`users` WHERE `users`.user_id = `".$this->table_name."`.user_id AND `".$this->table_name."`.user_id = $user_id ORDER BY post_id DESC";
		$results = $database->emteDirectQuery($query, 'select');

		return $results;
	}

	public function updateUserPost($data, $files) {
		$database = new Database();

		$post_id = $database->escapeString($data['post_id']);
        $user_id = $database->escapeString($data['user_id']);
        $post = $database->escapeString($data['post']);

        $query = "UPDATE `".$this->table_name."` SET post = '".$post."' WHERE post_id = $post_id AND user_id = $user_id";
        $result = $database->emteDirectQuery($query, 'update');

        return $result;
	}

	public function deletePost($data) {
		$database = new Database();

		$user_id = intval($data['user_id']);
		$post_id = intval($data['post_id']);

		$query = "DELETE FROM `".$this->table_name."` WHERE user_id = $user_id AND post_id = $post_id";
		$result = $database->emteDirectQuery($query, 'delete');

		return $result;
	}

	public function getCommentById($post_id) {
        $database = new Database();

        $post_id = intval($post_id);
        $query = "SELECT `users`.*, `comments`.* FROM `users`, `comments` WHERE `users`.user_id = `comments`.user_id AND `comments`.post_id = $post_id";
        $results = $database->emteDirectQuery($query, 'select');

        return $results;
	}
}
/* End post */

/* Reactions */
class React {
	protected $table_name = "reactions";

	public function like($data) {
        $database = new Database();

        $post_id = $database->escapeString($data['post_id']);
        $user_id = $database->escapeString($data['user_id']);

        if($this->checkIfUserHasReactions($data)) {
        	$query = "UPDATE `".$this->table_name."` SET status = 1 WHERE post_id = $post_id AND user_id = $user_id";
            $result = $database->emteDirectQuery($query, 'update');
        } else {
        	$query = "INSERT INTO `".$this->table_name."`(post_id, user_id, status) VALUES($post_id, $user_id, 1)";
            $result = $database->emteDirectQuery($query, 'insert');
        }

        return $result;
	}

	public function dislike($data) {
        $database = new Database();

        $post_id = $database->escapeString($data['post_id']);
        $user_id = $database->escapeString($data['user_id']);

        if($this->checkIfUserHasReactions($data)) {
        	$query = "UPDATE `".$this->table_name."` SET status = 2 WHERE post_id = $post_id AND user_id = $user_id";
            $result = $database->emteDirectQuery($query, 'update');
        } else {
        	$query = "INSERT INTO `".$this->table_name."`(post_id, user_id, status) VALUES($post_id, $user_id, 2)";
            $result = $database->emteDirectQuery($query, 'insert');
        }

        return $result;
	}

	public function checkIfUserHasReactions($data) {
		$database = new Database();

		$post_id = $database->escapeString($data['post_id']);
		$user_id = $database->escapeString($data['user_id']);

		$query = "SELECT * FROM `".$this->table_name."` WHERE user_id = $user_id AND post_id = $post_id";
		$result = $database->emteDirectQuery($query, 'select');

		return $result;
	}

	public function getUserReactions($user_id) {
        $database = new Database();

        $user_id = intval($user_id);
        $query = "SELECT * FROM `".$this->table_name."` WHERE user_id = $user_id";
        $result = $database->emteDirectQuery($query, 'select');

        return $result;
	}
}
/* End reactions */

class Comment {
	public $table_name = "comments";

	public function save($data) {
       $database = new Database();

       $post_id = intval($data['post_id']);
       $user_id = intval($data['user_id']);
       $comment = $database->escapeString($data['comment']);

       $query = "INSERT INTO `".$this->table_name."`(user_id, post_id, comment) VALUES($user_id, $post_id, '".$comment."')";
       $result = $database->emteDirectQuery($query, 'insert');

       return $result;
	}

	public function editComment($data) {
	   $database = new Database();

	   $user_id = intval($data['user_id']);
	   $comment_id = intval($data['comment_id']);
	   $comment = $database->escapeString($data['comment']);

	   $query = "UPDATE `".$this->table_name."` SET comment = '".$comment."' WHERE comment_id = $comment_id AND user_id = $user_id";
	   $result = $database->emteDirectQuery($query, 'update');

	   return $result;
	}

	public function deleteComment($data) {
		$database = new Database();

		$user_id = intval($data['user_id']);
		$comment_id = intval($data['comment_id']);

		$query = "DELETE FROM `".$this->table_name."` WHERE comment_id = $comment_id AND user_id = $user_id";
		$result = $database->emteDirectQuery($query, 'delete');

		return $result;
	}

	public function getCommentById($comment_id) {
		$database = new Database();

		$comment_id = intval($comment_id);
		$query = "SELECT * FROM `".$this->table_name."` WHERE comment_id = $comment_id";
		$result = $database->emteDirectQuery($query, 'select');

		return $result;
	}
}

class Message {
	public $table_name = "message";

	public function message($data) {
		$database = new Database();

		$user_id = intval($data['user_id']);
		$friend_id = intval($data['friend_id']);
		$message = $database->escapeString($data['message']);

		$query = "INSERT INTO `".$this->table_name."`(user_id, friend_id, message) VALUES($user_id, $friend_id, '".$message."')";
		$result = $database->emteDirectQuery($query, 'insert');

		$message_id = $database->last_insert_id;
 
        if($message_id) {
        	$database = new Database();

        	$query = "SELECT `message`.* FROM `message` WHERE user_id = $user_id OR friend_id = $user_id AND status = 0 ORDER BY message_id DESC";
		    $result = $database->emteDirectQuery($query, 'select');

		    return $result;
        }
	}

	public function getMessage($user_id, $friend_id) {
        $database = new Database();

    	$query = "SELECT `message`.* FROM `message` WHERE user_id = $user_id OR friend_id = $user_id AND status = 0 ORDER BY message_id DESC";
	    $result = $database->emteDirectQuery($query, 'select');

	    return $result;
	}

	public function getMessages($user_id) {
        $database = new Database();

    	$query = "SELECT `users`.*, `".$this->table_name."`.* FROM `users`,`".$this->table_name."` WHERE `".$this->table_name."`.friend_id = `users`.user_id AND `".$this->table_name."`.user_id = $user_id GROUP BY `".$this->table_name."`.friend_id ";
	    $result = $database->emteDirectQuery($query, 'select');

	    if($result) {
	    	return $result;
	    } else {
	    	$database = new Database();

	    	$query = "SELECT `users`.*, `".$this->table_name."`.* FROM `users`,`".$this->table_name."` WHERE `".$this->table_name."`.user_id = `users`.user_id AND `".$this->table_name."`.user_id != $user_id AND `".$this->table_name."`.friend_id = $user_id GROUP BY `".$this->table_name."`.user_id ";
	        $result = $database->emteDirectQuery($query, 'select');

	        return $result;
	    }
	}
}

?>