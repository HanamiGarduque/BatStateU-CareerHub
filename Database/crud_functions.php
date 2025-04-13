<?php
class Users
{
    private $conn;
    private $tbl_name = "users";

    public $user_id;
    public $first_name;
    public $last_name;
    public $job_title;
    public $email;
    public $address;
    public $phone_number;
    public $roles;
    public $status;
    public $password;
    public $company_name;
    public $bio;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if the account is suspended
    public function checkAccStatus($email)
    {
        $query = "SELECT status FROM " . $this->tbl_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        // Bind the username to the query
        $stmt->bindParam('email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($row && $row['status'] === 'banned');
    }

    // Check if email already exists
    public function checkDuplicateAcc()
    {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Create a new user
    public function create()
    {
        if ($this->checkDuplicateAcc()) {
            echo "Username or Email already exists.";
            return false;
        }

        $query = "INSERT INTO " . $this->tbl_name . " (first_name, last_name, email, address, phone_number, roles, status, password) 
                  VALUES (:first_name, :last_name, :email, :address, :phone_number, :roles, :status, :password)";

        $stmt = $this->conn->prepare($query);

        $defaultStatus = 'active';

        // Hash password before storing
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':roles', $this->roles);
        $stmt->bindParam(':status', $defaultStatus);
        $stmt->bindParam(':password', $this->password);

        return $stmt->execute();
    }
    public function retrieveProfile($user_id)
    {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function updateProfile($user_id)
    {
        $query = "UPDATE " . $this->tbl_name . " 
              SET company_name = :company_name, job_title = :job_title, bio = :bio
              WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':job_title', $this->job_title);
        $stmt->bindParam(':bio', $this->bio);

        if ($stmt->execute()) {
            return true;
        }
        return false;
        
    }
}

class Jobs
{
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


    public function __construct($db)
    {
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
class Bookmarks
{
    private $conn;
    private $tbl_name = "saved_jobs";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function isBookmarked($user_id, $job_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM . $this->tbl_name WHERE user_id = :user_id AND job_id = :job_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function add($user_id, $job_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO . $this->tbl_name (user_id, job_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $job_id]);
    }

    public function remove($user_id, $job_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM . $this->tbl_name WHERE user_id = ? AND job_id = ?");
        return $stmt->execute([$user_id, $job_id]);
    }
}
?>