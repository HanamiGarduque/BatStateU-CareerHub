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
    public $title;

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
        return ($row && $row['status'] === 'suspended');
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
            $stmt = $this->conn->prepare("CALL update_user_info(:user_id, :address, :phone_number, :bio, :title)");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':phone_number', $this->phone_number);
            $stmt->bindParam(':bio', $this->bio);
            $stmt->bindParam(':title', $this->title);

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

    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("CALL get_all_users()");
        $stmt->execute(); // no parameters needed
        return $stmt;
    }

    public function updateUserStatus($user_id, $status)
    {
        $stmt = $this->conn->prepare("CALL update_user_status(:user_id, :new_status)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':new_status', $status);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Failed to update user status.";
            return false;
        }
    }
}
class Jobs
{
    private $conn;

    public $job_id;
    public $user_id;
    public $title;
    public $job_category;
    public $company_name;
    public $location;
    public $type;
    public $salary_min;
    public $salary_max;
    public $description;
    public $responsibilities;
    public $requirements;
    public $benefits_perks;



    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function retrieveJobs($user_id) // done
    {
        $stmt = $this->conn->prepare("CALL get_jobs(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    public function retrieveRecentJobs($user_id) // done
    {
        $stmt = $this->conn->prepare("CALL get_recent_jobs(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    public function countAllJobs()
    {
        $stmt = $this->conn->prepare("CALL count_all_jobs(@total)");
        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->conn->query("SELECT @total AS total");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countAllJobsPerEmp($emp_id)
    {
        $stmt = $this->conn->prepare("CALL get_job_count_per_employer(:employer_id)");
        $stmt->execute([':employer_id' => $emp_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row['total'];
    }
    public function searchJobs($keyword = '')
    {
        $stmt = $this->conn->prepare("CALL search_jobs(:keyword)");
        $stmt->bindValue(':keyword', $keyword);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $results;
    }

    public function retrieveJobById($job_id)
    {
        $stmt = $this->conn->prepare("CALL get_job_by_ID(:job_id)");
        $stmt->execute([':job_id' => $job_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function createJob($user_id) // done
    {

        $stmt = $this->conn->prepare("CALL create_job(:user_id, :title, :job_category, :company_name, :location, :type, :salary_min, :salary_max, :description, :responsibilities, :requirements, :benefits_perks)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':job_category', $this->job_category);
        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':salary_min', $this->salary_min);
        $stmt->bindParam(':salary_max', $this->salary_max);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':responsibilities', $this->responsibilities);
        $stmt->bindParam(':requirements', $this->requirements);
        $stmt->bindParam(':benefits_perks', $this->benefits_perks);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Failed to create job.";
            return false;
        }

        return $stmt->execute();
    }
    public function getJobById($job_id)
    {
        $stmt = $this->conn->prepare("CALL get_job_by_id(:job_id)");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function updateJobById($job_id)
    {
        $stmt = $this->conn->prepare("CALL update_job(:job_id, :user_id, :title, :job_category, :company_name, :location, :type, :salary_min, :salary_max, :description, :responsibilities, :requirements, :benefits_perks)");

        $stmt->bindParam(':job_id', $job_id);
        $stmt->bindParam(':user_id', $this->user_id); // ✅ ADD THIS LINE
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':job_category', $this->job_category);
        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':salary_min', $this->salary_min);
        $stmt->bindParam(':salary_max', $this->salary_max);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':responsibilities', $this->responsibilities);
        $stmt->bindParam(':requirements', $this->requirements);
        $stmt->bindParam(':benefits_perks', $this->benefits_perks);

        return $stmt->execute();
    }
    public function deleteJob($job_id)
    {
        $stmt = $this->conn->prepare("CALL delete_job(:job_id)");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        return $stmt->execute();
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
        $this->conn->prepare("CALL is_bookmarked(:user_id, :job_id, @is_marked)")
            ->execute([':user_id' => $user_id, ':job_id' => $job_id]);

        $stmt = $this->conn->query("SELECT @is_marked AS is_marked");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (bool)$result['is_marked'];
    }

    public function addBookmark($user_id, $job_id)
    {
        $stmt = $this->conn->prepare("CALL add_bookmark(:user_id, :job_id)");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':job_id' => $job_id
        ]);
    }
    public function removeByUser($user_id, $job_id)
    {
        $stmt = $this->conn->prepare("CALL delete_bookmark_by_user(:user_id, :job_id)");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':job_id' => $job_id
        ]);
    }

    public function removeBySavedJob($user_id, $saved_jobs_id)
    {
        $stmt = $this->conn->prepare("CALL delete_bookmark_by_saved_job(:user_id, :saved_jobs_id)");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':saved_jobs_id' => $saved_jobs_id
        ]);
    }

    public function retrieveBookmarks($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_saved_jobs_by_user_id(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $results;
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
class JobApplication
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function createApplication($job_id, $user_id, $cover_letter, $resume_path)
    {
        try {
            $stmt = $this->conn->prepare("CALL CreateApplication(:job_id, :user_id, :cover_letter, :resume_path, :status)");
            $stmt->execute([
                ':job_id' => $job_id,
                ':user_id' => $user_id,
                ':cover_letter' => $cover_letter,
                ':resume_path' => $resume_path,
                ':status' => 'Under Review' // Default status
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function hasAlreadyApplied($job_id, $user_id)
    {
        $this->conn->prepare("CALL has_already_applied(:job_id, :user_id, @already_applied)")
            ->execute([
                ':job_id' => $job_id,
                ':user_id' => $user_id
            ]);

        $result = $this->conn->query("SELECT @already_applied AS already_applied");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return (bool)$row['already_applied'];
    }

    public function retrieveApplicationsByEmpID($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_applications_by_emp_id(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    public function retrieveApplicationsByJobID($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_applications_by_job_id(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }


    public function updateApplicationStatus($application_id, $status)
    {
        try {
            $stmt = $this->conn->prepare("CALL update_application_status(:application_id, :status)");
            $stmt->bindParam(':application_id', $application_id);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Databas  e error: " . $e->getMessage());
            return false;
        }
    }
    public function insertToStatusLog($user_id, $status)
    {
        try {
            $stmt = $this->conn->prepare("CALL insert_to_status_log(:user_id, :status)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function getApplicationById($application_id)
    {
        $stmt = $this->conn->prepare("CALL get_application_status_by_id(:application_id)");
        $stmt->execute([':application_id' => $application_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function retrieveNoOfApplications($job_id)
    {
        $stmt = $this->conn->prepare("CALL get_no_of_applications(:job_id)");
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    public function retrieveNoOfApplicationsPerStatus($status, $job_id)
    {
        $stmt = $this->conn->prepare("CALL get_no_of_applications_per_status(:status, :job_id)");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getTotalJobApplications($emp_id)
    {
        $stmt = $this->conn->prepare("CALL get_total_applications(:emp_id)");
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['total'] ?? 0;
    }
    public function getTotalPositionsFilled($emp_id)
    {
        $stmt = $this->conn->prepare("CALL get_total_positions_filled(:emp_id)");
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['total'] ?? 0;
    }
    public function retrieveRecentApplications($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_recent_applications(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function retrieveUserApplications($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_applications(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
class Education
{
    private $conn;

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
    public function retrieveExperiences($user_id)
    {
        $stmt = $this->conn->prepare("CALL get_jobseeker_experience(:user_id)");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt;
    }
    public function addExperiences($user_id, $job_title, $company_name, $start_date, $end_date, $description)
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
    public function deleteExperience($id, $user_id)
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

    public function uploadResume($userId, $filename, $target_path, $extension)
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
    public function deleteResume($userId)
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

class StatusLog
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function retrieveStatusLog($application_id)
    {
        $stmt = $this->conn->prepare("CALL get_status_log_by_application(:application_id)");
        $stmt->execute([':application_id' => $application_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
