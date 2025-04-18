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
    public function checkAccStatus($email) // done
    {
        $stmt = $this->conn->prepare("CALL get_user_status(:email)");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['status'] === 'banned');
    }

    // Check if email already exists
    public function checkDuplicateAcc() // done
    {
        $stmt = $this->conn->prepare("CALL get_user_email(:email)");
        $stmt->execute([':email' => $this->email]);
        return $stmt->rowCount() > 0;
    }

    // Create a new user
    public function create() // done
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

    public function retrieveProfileByEmail($email) // done
    {
        $stmt = $this->conn->prepare("CALL get_user_by_email(:email)");
        $stmt->execute([':email' => $email]);
        return $stmt;
    }

    public function retrieveProfileById($user_id) // done
    {
        $stmt = $this->conn->prepare("CALL get_user_by_ID(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }

    public function updateProfile() // done
    {
        try {
            $stmt = $this->conn->prepare("CALL update_user_info(:user_id, :address, :phone_number, :bio)");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':phone_number', $this->phone_number);
            $stmt->bindParam(':bio', $this->bio);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Failed to update user profile.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
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

class Employers
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

    public function updateProfile($user_id) // done
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

    public function retrieveEmpProfile($user_id) // done
    {
        $stmt = $this->conn->prepare("CALL get_employer_profile(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }
}

class Education
{
    private $conn;
    private $tbl_name = "Educations";

    public $id;
    public $user_id;
    public $degree;
    public $institution;
    public $start_date;
    public $end_date;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addEducation($user_id, $degree, $institution, $start_date, $end_date, $description)
    {
        $stmt = $this->conn->prepare("CALL insert_jobseeker_education(:user_id, :degree, :institution, :start_date, :end_date, :description)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':degree', $degree);
        $stmt->bindParam(':institution', $institution);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    public function retrieveEducationalBackground($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_educational_background(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }

    public function deleteEducation($id)
    {
        $stmt = $this->conn->prepare("CALL delete_jobseeker_education(:id)");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
class Experiences
{
    private $conn;

    public $id;
    public $user_id;
    public $job_title;
    public $company_name;
    public $start_date;
    public $end_date;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function retrieveExperiences($user_id) // done 
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_experience(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }
    public function addExperiences($user_id, $job_title, $company_name, $start_date, $end_date, $description) // done
    {
        $stmt = $this->conn->prepare("CALL insert_jobseeker_experience(:user_id, :job_title, :company_name, :start_date, :end_date, :description)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':job_title', $job_title);
        $stmt->bindParam(':company_name', $company_name);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }
    public function deleteExperience($id, $user_id) // done
    {
        $stmt = $this->conn->prepare("CALL delete_jobseeker_experience(:id, :user_id)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}

class Resumes
{
    private $conn;

    public $id;
    public $user_id;
    public $file_name;
    public $file_path;
    public $file_extension;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function uploadResume($userId, $filename, $target_path, $extension) // done
    {

        $stmt = $this->conn->prepare("CALL upload_jobseeker_resume(:user_id, :file_name, :file_path, :file_extension)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':file_name', $filename);
        $stmt->bindParam(':file_path', $target_path);
        $stmt->bindParam(':file_extension', $extension);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteResume($userId) // done
    {

        $stmt = $this->conn->prepare("CALL delete_jobseeker_resume(:user_id)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
class Skills
{
    private $conn;

    public $id;
    public $user_id;
    public $skill_name;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function retrieveSkills($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_skills(:user_id)");
        $stmt->execute([':user_id' => $user_id]);

        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

        do {
            $stmt->nextRowset();
        } while ($stmt->columnCount());

        $stmt->closeCursor();

        return $skills;
    }


    public function addSkill($user_id, $skill_name) // done 
    {
        $stmt = $this->conn->prepare("CALL insert_jobseeker_skill(:user_id, :skill_name)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':skill_name', $skill_name);
        return $stmt->execute();
    }
    public function checkduplicateSkill($user_id, $skill_name)
    {
        $stmt = $this->conn->prepare("CALL check_duplicate_skill(:user_id, :skill_name)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':skill_name', $skill_name);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function deleteSkill($user_id, $skill_id)
    {
        $stmt = $this->conn->prepare("CALL delete_jobseeker_skill(:user_id, :skill_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':skill_id', $skill_id);
        return $stmt->execute();
    }
}
