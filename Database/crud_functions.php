<?php
class Users {
    private $conn;
    private $tbl_name = "users";

    public $user_id;
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
    public function checkAccStatus($email) {
        $query = "SELECT status FROM " . $this->tbl_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
        // Bind the username to the query
        $stmt->bindParam('email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($row && $row['status'] === 'banned');
    }
    
    // Check if email already exists
    public function checkDuplicateAcc() {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
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
    
        $query = "INSERT INTO " . $this->tbl_name . " (first_name, last_name, email, address, phone_number, roles, status, password) 
                  VALUES (:first_name, :last_name, :email, :address, :phone_number, :roles, :status, :password)";
        
        $stmt = $this->conn->prepare($query);

        // Set default values for role and status
        $defaultRole = 'jobseeker'; // Change if needed
        $defaultStatus = 'active';

        // Hash password before storing
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

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
class Jobs {
    private $conn;
    private $tbl_name = "jobs";

    public $job_id;
public $title;
public $company_name;
public $location;
public $type;
public $salary_min;
public $salary_max;
public $description;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function retrieveJobs()
    {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>