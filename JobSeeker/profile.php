<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php'); // Or wherever you want
    exit();
}
$database = new Database();
$db = $database->getConnect();

$jobseeker = new Users($db);
$user_id = $_SESSION['id'];

// Get the statement from the method
$stmt = $jobseeker->retrieveProfileById($user_id);

$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    // User not found, log out
    session_destroy();
    header("Location: login.php?error=invalid_user");
    exit();
}
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'personal';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - My Profile</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo"></div>
                <h3>Career Hub</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="homepage.php"><i class="fas fa-search"></i> Find Jobs</a>
                    </li>
                    <li class="active">
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a href="applications_management.php"><i class="fas fa-file-alt"></i> My Applications</a>
                    </li>
                    <li>
                        <a href="saved_jobs.php"><i class="fas fa-bookmark"></i> Saved Jobs</a>
                    </li>
                    <li class="logout">
                        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="dashboard-header">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="../placeholder.jpg" alt="Profile Picture">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Employer'; ?></span>
                    </div>
                </div>
            </header>
            <div class="dashboard-content">
                <h1>My Profile</h1>

                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-info">
                            <h2><?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']); ?></h2>
                            <p class="profile-title">Software Developer</p>
                            <p class="profile-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($userData['address']); ?></p>
                        </div>
                    </div>

                    <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="personal">Personal Info</button>
                        <button class="tab-btn" data-tab="resume">Resume</button>
                        <button class="tab-btn" data-tab="skills">Skills & Experience</button>
                        <button class="tab-btn" data-tab="education">Education</button>
                    </div>

                    <div class="tab-pane active" id="personal">
                        <div class="personal-info-container">
                            <form method="POST" action="update_profile.php">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?> <?php echo htmlspecialchars($userData['last_name']); ?>" required readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?php echo htmlspecialchars($userData['address']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>About Me</label>
                                    <textarea name="bio" rows="4"><?php echo htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="save-btn">Save</button>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane" id="resume">
                        <h3>Resume</h3>

                        <!-- Upload Form -->
                        <form action="resume_handler.php" method="POST" enctype="multipart/form-data" class="resume-upload-form">
                            <div class="form-group">
                                <label for="resumeFiles">Upload Resume</label>
                                <input type="file" name="resume" id="resumeFiles" class="form-control" accept=".pdf,.doc,.docx" required>
                            </div>
                            <input type="hidden" name="action" value="upload">
                            <button type="submit" class="btn btn-primary">Upload Resume</button>
                        </form>

                        <!-- Uploaded Resume Section -->
                        <?php
                        // Directory for resumes with this user's ID
                        $userResumeDir = "resumes/user_" . $user_id;

                        // Create directory if it doesn't exist
                        if (!file_exists($userResumeDir) && !is_dir($userResumeDir)) {
                            mkdir($userResumeDir, 0755, true);
                        }

                        // Get all files in the user's resume directory
                        $userResumes = [];
                        if (is_dir($userResumeDir)) {
                            $files = scandir($userResumeDir);
                            foreach ($files as $file) {
                                if ($file != "." && $file != "..") {
                                    $userResumes[] = $file;
                                }
                            }
                        }

                        // If no resumes, also check the old method (single file in main directory)
                        if (empty($userResumes)) {
                            $extensions = ['pdf', 'doc', 'docx'];
                            foreach ($extensions as $ext) {
                                $path = "resumes/$user_id.$ext";
                                if (file_exists($path)) {
                                    $userResumes[] = basename($path);
                                }
                            }
                        }

                        // Display all resumes
                        if (!empty($userResumes)): ?>
                            <h4>Your Uploaded Resumes</h4>
                            <div class="resume-list">
                                <?php foreach ($userResumes as $resume): ?>
                                    <div class="resume-item">
                                        <span class="file-name"><?= htmlspecialchars($resume) ?></span>
                                        <div class="resume-buttons">
                                            <!-- View Resume -->
                                            <a href="<?= htmlspecialchars("$userResumeDir/$resume") ?>" target="_blank" class="btn btn-success" title="View Resume">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <!-- Delete Resume -->
                                            <form action="resume_handler.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="file_name" value="<?= htmlspecialchars($resume) ?>">
                                                <button type="submit" class="btn btn-danger" title="Delete Resume">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-resumes">No resumes uploaded yet.</p>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane" id="skills">
                        <div class="skills-experience-container">
                            <!-- Skills Section -->
                            <div class="skills-list">
                                <h3>Skills</h3>
                                <div class="skills-display">
                                    <?php
                                    try {
                                        $stmt->closeCursor();
                                        $skillsClass = new Skills($db);
                                        $skills = $skillsClass->retrieveSkills($user_id);

                                        if ($skills) {
                                            echo "<ul class='skills-ul'>";
                                            foreach ($skills as $skill) {
                                                echo "<li class='skill-item'>";
                                                echo "<span class='skill-name'>" . htmlspecialchars($skill['skill_name']) . "</span>";
                                                echo "<form method='POST' action='delete_skill.php' class='delete-skill-form'>";
                                                echo "<input type='hidden' name='id' value='" . $skill['id'] . "'>";
                                                echo "<button type='submit' class='delete-btn'>&times;</button>";
                                                echo "</form>";
                                                echo "</li>";
                                            }
                                            echo "</ul>";
                                        } else {
                                            echo "<p>No skills added yet.</p>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<p>Error loading skills: " . $e->getMessage() . "</p>";
                                    }
                                    ?>


                                </div>
                                <form method="POST" action="add_skill.php" class="add-skill-form">
                                    <input type="text" name="skill" placeholder="Add a new skill" required>
                                    <button type="submit" class="add-skill-btn">Add</button>
                                </form>
                            </div>
                            <!-- Experience Section -->
                            <div class="experience-section">
                                <h3>Work Experience</h3>

                                <?php
                                try {
                                    $experience = new Experiences($db);
                                    $stmt = $experience->retrieveExperiences($user_id);
                                    $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $stmt->closeCursor(); // Close the cursor to free up the connection 

                                    if ($experiences) {
                                        foreach ($experiences as $experience) {
                                            echo "  
                                        <div class='experience-item'>
                                            <div class='experience-header'>
                                                <h4>" . htmlspecialchars($experience['job_title']) . "</h4>
                                                <div class='experience-actions'>                                                    
                                                    <!-- Delete Form -->
                                                    <form method='POST' action='delete_experience.php' style='display: inline;' onsubmit=\"return confirm('Are you sure you want to delete this experience?');\">
                                                        <input type='hidden' name='id' value='" . $experience['id'] . "'>
                                                        <button type='submit' class='delete-experience' title='Delete'><i class='fas fa-trash'></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <p class='company-name'>" . htmlspecialchars($experience['company_name']) . "</p>
                                            <p class='experience-date'>" . htmlspecialchars($experience['start_date']) . " - " . htmlspecialchars($experience['end_date']) . "</p>
                                            <p class='experience-description'>" . htmlspecialchars($experience['description']) . "</p>
                                        </div>";
                                        }
                                    } else {
                                        echo "<p>No work experience added yet.</p>";
                                    }
                                } catch (Exception $e) {
                                    echo "<p>Error loading work experience: " . $e->getMessage() . "</p>";
                                }
                                ?>

                                <!-- Add New Experience -->
                                <form method="POST" action="add_experience.php" class="add-experience-form">
                                    <h4>Add Work Experience</h4>
                                    <input type="text" name="job_title" placeholder="Job Title" required>
                                    <input type="text" name="company_name" placeholder="Company Name" required>
                                    <input type="date" name="start_date" placeholder="Start Date" required>
                                    <input type="date" name="end_date" placeholder="End Date" required>
                                    <textarea name="description" placeholder="Description of duties" required></textarea>
                                    <button type="submit" class="add-experience-btn"><i class="fas fa-plus"></i> Add Experience</button>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane" id="education">
                        <div class="education-container">
                            <?php
                            try {

                                $education = new Education($db);
                                $stmt = $education->retrieveEducationalBackground($user_id);
                                $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $stmt->closeCursor();
                                if ($educations) {
                                    foreach ($educations as $edu) {
                                        echo "
                                <div class='education-item'>
                                    <div class='education-header'>
                                        <h4>" . htmlspecialchars($edu['degree']) . "</h4>
                                        <div class='education-actions'>
                                            <form method='POST' action='delete_education.php' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this education record?');\">
                                                <input type='hidden' name='id' value='" . $edu['id'] . "'>
                                                <button type='submit' class='delete-education'><i class='fas fa-trash'></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class='institution'>" . htmlspecialchars($edu['institution']) . "</p>
                                    <p class='education-date'>" . htmlspecialchars($edu['start_date']) . " - " . htmlspecialchars($edu['end_date']) . "</p>
                                    <p class='education-description'>" . htmlspecialchars($edu['description']) . "</p>
                                </div>";
                                    }
                                } else {
                                    echo "<p>No education record added yet.</p>";
                                }
                            } catch (Exception $e) {
                                echo "<p>Error loading education history: " . $e->getMessage() . "</p>";
                            }
                            ?>

                            <!-- Add New Education Form -->
                            <form method="POST" action="add_education.php" class="add-education-form">
                                <h4>Add Education</h4>
                                <input type="text" name="degree" placeholder="Degree or Program" required>
                                <input type="text" name="institution" placeholder="Institution" required>
                                <input type="text" name="start_year" placeholder="Start Year (e.g. 2020)" required>
                                <input type="text" name="end_year" placeholder="End Year (e.g. 2024)" required>
                                <textarea name="description" placeholder="Brief description" required></textarea>
                                <button type="submit" class="add-education-btn"><i class="fas fa-plus"></i> Add Education</button>
                            </form>
                        </div>
                    </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    // Activate clicked button and matching pane
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Check for URL parameter to show success message
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('update') === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your profile has been updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                // Clean URL to prevent message from showing again on refresh
                history.replaceState({}, document.title, window.location.pathname);
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Get all tab buttons and tab content elements
            const tabButtons = document.querySelectorAll(".tab-btn");
            const tabPanes = document.querySelectorAll(".tab-pane");

            // Set the active tab based on the URL query parameter or default to 'personal'
            const activeTab = new URLSearchParams(window.location.search).get('tab') || 'personal';

            // Function to show the active tab content and hide others
            function showTabContent(tabId) {
                // Hide all tab content
                tabPanes.forEach(pane => pane.style.display = "none");

                // Show the selected tab content
                const activePane = document.getElementById(tabId);
                if (activePane) {
                    activePane.style.display = "block";
                }
            }

            // Function to activate the correct tab button
            function activateTabButton(tabId) {
                tabButtons.forEach(button => {
                    button.classList.remove("active");
                    if (button.dataset.tab === tabId) {
                        button.classList.add("active");
                    }
                });
            }

            // Initialize the first tab to show based on the activeTab
            showTabContent(activeTab);
            activateTabButton(activeTab);

            // Add event listeners to each tab button
            tabButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const tabId = button.dataset.tab;

                    // Change the URL with the tab query parameter
                    history.pushState(null, '', `profile.php?tab=${tabId}`);

                    // Show the content and activate the button
                    showTabContent(tabId);
                    activateTabButton(tabId);
                });
            });
        });
    </script>

</body>

</html>