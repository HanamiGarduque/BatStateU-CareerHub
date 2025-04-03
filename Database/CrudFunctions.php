<?php
class Users {
    private $conn;
    private $tbl_name = "users";

    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $phone_number;  
    public $roles;
    public $status;
    public $password;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if the account is suspended
    public function checkAccStatus($username) {
        $query = "SELECT status FROM " . $this->tbl_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        
        // Bind the username to the query
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($row && $row['status'] === 'banned');
    }
    
    // Check if username or email already exists
    public function checkDuplicateAcc() {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Create a new user
    public function create() {
        if ($this->checkDuplicateAcc()) {
            echo "Username or Email already exists.";
            return false;
        }
    
        $query = "INSERT INTO " . $this->tbl_name . " (username, first_name, last_name, email, address, phone_number, roles, status, password) 
                  VALUES (:username, :first_name, :last_name, :email, :address, :phone_number, :roles, :status, :password)";
        
        $stmt = $this->conn->prepare($query);

        // Set default values for role and status
        $defaultRole = 'jobseeker'; // Change if needed
        $defaultStatus = 'active';

        // Hash password before storing
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':roles', $defaultRole);
        $stmt->bindParam(':status', $defaultStatus);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    } 
}
?>
