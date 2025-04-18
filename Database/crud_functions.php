<?php
class Users
{
    private $conn;

    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $phone_number;
    public $roles;
    public $status;
    public $password;
    public $bio;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if the account is suspended
    public function checkAccStatus($email)
    {
        $stmt = $this->conn->prepare("CALL check_user_status(:email)");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['status'] === 'banned');
    }

    // Check if email already exists
    public function checkDuplicateAcc()
    {
        $stmt = $this->conn->prepare("CALL check_duplicate_email(:email)");
        $stmt->execute([':email' => $this->email]);
        return $stmt->rowCount() > 0;
    }

    // Create a new user
    public function create()
    {
        if ($this->checkDuplicateAcc()) {
            echo "Username or Email already exists.";
            return false;
        }

        try {
            $defaultStatus = 'active';

            $stmt = $this->conn->prepare("CALL create_user(:first_name, :last_name, :email, :address, :phone_number, :roles, :status, :password)");

            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':phone_number', $this->phone_number);
            $stmt->bindParam(':roles', $this->roles);
            $stmt->bindParam(':status', $defaultStatus);
            $stmt->bindParam(':password', $this->password);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Failed to create user.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function retrieveProfile($email)
    {
        $stmt = $this->conn->prepare("CALL retrieve_user_by_email(:email)");
        $stmt->execute([':email' => $email]);
        return $stmt;
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

class Employer
{
    private $conn;

    public $user_id;
    public $emp_id;
    public $company_name;
    public $job_title;
    public $bio;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function updateProfile($user_id)
    {
        try {
            $stmt = $this->conn->prepare("CALL update_employer_details(:user_id, :company_name, :job_title, :bio)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':company_name', $this->company_name);
            $stmt->bindParam(':job_title', $this->job_title);
            $stmt->bindParam(':bio', $this->bio);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Stored Procedure Failed: " . $e->getMessage());
            return false;
        }
    }

    public function retrieveEmpProfile($user_id)
    {
        $stmt = $this->conn->prepare("CALL retrieve_emp_profile(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }
}
