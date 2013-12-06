<?php
/**
 * 
 * PHP version 5
 * 
 * LICENSE: This source file is subject to the MIT License, available
 * at http://www.opensource.org/licenses/mit-license.html
 * 
 * @author      Björn Ax <bjorn.ax@gmail.com>
 * @copyright   2013 Björn Ax
 * @license     http://www.opensource.org/licenses/mit-license.html
 */

class user extends DB_Connect {
    /*
     * Send user a new password when using the forgot password page
     */
    public function forgot_password($mail) {
        $mail = $_POST['mail'];
        $exist = $this->email_exists($mail);
        
        if($exist == 1){
            return false;
        }
        else {
            
        }
    }
    
    /*
     * Get comments to an blogg post
     */
    public function get_comments($id) {
        $sql = "SELECT * FROM blogg_comment WHERE parent_id = :id && visible = 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
            return $r[0] == "" ? false : $r;
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }
    
    /*
     * Counts number of comments to an blogg post
     */
    public function count_comments($id) {
        $sql = 'SELECT COUNT(id) FROM blogg_comment WHERE parent_id = :id';
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $r = $stmt->fetch();
            
            return $r[0];
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
    /*
     * Gets the blogg posts
     * Can be sent an int to get a specific blogg post
     */
    public function get_blogg($id) {
        $sql = 'SELECT * FROM blogg';
        
        if($id == 0) {
            $sql .= ' WHERE visible = 1 ORDER BY date DESC';
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $r;
            }
            catch(Exception $e) {
                die($e->getMessage());
            }                      
         }
         else{
             $sql .= ' WHERE id = :id';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                $r = $stmt->fetch();
                
                return $r;
            }
            catch(Exception $e) {
                die($e->getMessage());
            }
        }
    }
    
    /*
     * Activate the user
     */
    public function activate_user($code) {
        $sql = "UPDATE users SET aktcode = 0, active = 1 WHERE aktcode = :kod";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":kod", $code);
            $stmt->execute();
            $r = $stmt->fetch();
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
        
        return $r == 1 ? true : false;
    }
    
    /*
     * Protect pages that only an admin is allowed to see
     */
    public function admin_protect() {
        global $user_data;    
  
        if($this->is_admin($user_data['user_id']) == 0) {
            header("Location: index.php");
            exit();
        }
    }
    
    /*
     * Check if user is logged in and redirect from pages that logged
     * in user can't access
     */
    public function logged_in_redirect() {
	if($this->logged_in() === true) {
            header("Location: index.php");
            exit();		
	}
    }
    
    /*
     * Protects pages that only logged in user can access
     */
    public function protect_page() {
        if($this->logged_in() === false)
        {
            header("Location: protected.php");
            exit();
        }
    }
    
    /*
     * Get user_id from username
     */
    public function user_id_from_username($username) {
        $sql = "SELECT id FROM users WHERE username = :user";
        
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":user", $username);
            $stmt->execute();
            $result = $stmt->fetch();
            $stmt->closeCursor();
        } catch(Exception $e) {
            die($e->getMessage());
          }
        return $result;
    }
    
    /*
     * Get the users data
     */
    public function user_data($user_id) {
        $data = array();
        $user_id = (int)$user_id;
  
        $func_num_args = func_num_args();
        $func_get_args = func_get_args();
  
        if($func_num_args > 1) {            
            unset($func_get_args[0]);                    
        }
        
        $fields = implode(", ", $func_get_args);
        $sql = "SELECT $fields FROM users WHERE id = :user_id";
        
        try{
            $data = $this->db->prepare($sql);
            $data->bindParam(":user_id", $user_id);
            $data->execute();
            $result = $data->fetch();
            $data->closeCursor();
        } catch(Exception $e) {
            die($e->getMessage());
        }
        return $result;
    }
    
    /*
     * Login the user
     */
    public function login($username, $password) {
        $user_id = $this->user_id_from_username($username);        
        $password = $this->crypt_password($password);

        try {
            $query = $this->db->prepare("SELECT COUNT(id) AS id FROM users WHERE username = :user AND password = :pass");
            $query->execute(array("user" => $username, "pass" => $password));
            $r = $query->fetch();
        } catch(Exception $e) {
            die($e-getMessage());
        }

        return ($r['id'] == 1) ? $user_id : false;
    }
    
    /*
     * Check if user is admin
     */
    public function is_admin($user_id) {
        $user_id = (int)$user_id;
        
        $sql = "SELECT admin FROM users WHERE id = :id";
        
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(":id", $user_id);
            $query->execute();
            $r = $query->fetch();
            $query->closeCursor();
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
        
        return $r['admin'];
    }
    
    /*
     * Check if user is logged in
     */
    public function logged_in() {
        return (isset($_SESSION['user_id'])) ? true : false;
    }
    
    /*
     * Crypt user password for login and registration
     */
    public function crypt_password($password) {
        $salt = '';
        $password = crypt($password, $salt);
        
        return $password;
    }
    
    /*
     * Returns the number of registered users
     */
    public function count_users() {
        $sql = "SELECT COUNT(id) FROM users WHERE active = 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $r = $stmt->fetch();
            
            return $r;
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
    
    /*
     * Check if a user exists
     */
    public function user_exist($user) {
        $sql = "SELECT COUNT(id) FROM users WHERE username = :user";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":user", $user);
            $stmt->execute();
            $r = $stmt->fetch();
            
            return $r[0];
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
    
    /*
     * Prints a list if there are any errors
     */
    public function output_errors($errors){
        $output = array();
        foreach($errors as $error) {
            $output[] = '<li>'.$error.'</li>';
        }
        
        return '<ul>'.implode('', $output).'</ul>';        
    }
    
    /*
     * Check if the email exists
     */
    public function email_exists($email) {
        $sql = "SELECT COUNT(id) FROM users WHERE email = :email";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->execute();
            $r = $stmt->fetch();
            
            return $r[0];
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
    
    /*
     * Register a user
     */
    public function register_user($register_data) {
        $sql = "INSERT INTO users (username, password, f_name, l_name, email, aktcode, date_registered) VALUES (:user, :pass, :fname, :lname, :email, :aktkod, NOW())";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("user", $register_data['user']);
            $stmt->bindParam("pass", $this->crypt_password($register_data['pass']));
            $stmt->bindParam("fname", $register_data['fname']);
            $stmt->bindParam("lname", $register_data['lname']);
            $stmt->bindParam("email", $register_data['email']);
            $stmt->bindParam("aktkod", $register_data['aktkod']);
            $stmt->execute();
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
?>
