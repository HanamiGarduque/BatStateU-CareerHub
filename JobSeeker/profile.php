<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - My Profile</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-container">
                <div class="logo"></div>
                <h3>Career Hub</h3>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="homepage.php"><i class="fas fa-search"></i> Find Jobs</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a href="applications.php"><i class="fas fa-file-alt"></i> My Applications</a>
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

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search for jobs...">
                </div>
                <div class="user-menu">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="../placeholder.jpg" alt="Profile Picture">
                        <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; ?></span>
                    </div>
                </div>
            </header>

            <!-- Profile Content -->
            <div class="dashboard-content">
                <h1>My Profile</h1>
                
                <div class="profile-container">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-picture-container">
                            <img src="../placeholder.jpg" alt="Profile Picture">
                            <button class="change-photo-btn"><i class="fas fa-camera"></i> Change Photo</button>
                        </div>
                        <div class="profile-info">
                            <h2>John Doe</h2>
                            <p class="profile-title">Software Developer</p>
                            <p class="profile-location"><i class="fas fa-map-marker-alt"></i> Batangas City, Philippines</p>
                            <div class="profile-completion">
                                <div class="completion-text">Profile Completion: 75%</div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 75%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Tabs -->
                    <div class="profile-tabs">
                        <button class="tab-btn active" data-tab="personal">Personal Information</button>
                        <button class="tab-btn" data-tab="resume">Resume</button>
                        <button class="tab-btn" data-tab="skills">Skills & Experience</button>
                        <button class="tab-btn" data-tab="education">Education</button>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane active" id="personal">
                            <form class="profile-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" value="John" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" value="Doe" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" value="john.doe@g.batstate-u.edu.ph" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" value="09123456789" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" value="123 Main St, Batangas City" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Professional Title</label>
                                    <input type="text" value="Software Developer" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>About Me</label>
                                    <textarea rows="4">Passionate software developer with a strong foundation in web technologies and a keen interest in creating user-friendly applications. Currently pursuing a degree in Computer Science at Batangas State University.</textarea>
                                </div>
                                
                                <button type="submit" class="save-btn">Save Changes</button>
                            </form>
                        </div>
                        
                        <!-- Resume Tab -->
                        <div class="tab-pane" id="resume">
                            <div class="resume-container">
                                <div class="current-resume">
                                    <h3>Current Resume</h3>
                                    <div class="resume-file">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="resume-info">
                                            <p class="resume-name">John_Doe_Resume.pdf</p>
                                            <p class="resume-date">Uploaded on May 10, 2023</p>
                                        </div>
                                        <div class="resume-actions">
                                            <button class="view-resume-btn"><i class="fas fa-eye"></i> View</button>
                                            <button class="delete-resume-btn"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="upload-resume">
                                    <h3>Upload New Resume</h3>
                                    <p>Accepted file formats: PDF, DOCX (Max size: 5MB)</p>
                                    <div class="upload-area">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Drag and drop your resume here or</p>
                                        <label for="resume-upload" class="upload-btn">Browse Files</label>
                                        <input type="file" id="resume-upload" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Skills & Experience Tab -->
                        <div class="tab-pane" id="skills">
                            <div class="skills-experience-container">
                                <!-- Skills Section -->
                                <div class="skills-section">
                                    <h3>Skills</h3>
                                    <div class="skills-list">
                                        <div class="skill-tag">HTML <button class="remove-skill"><i class="fas fa-times"></i></button></div>
                                        <div class="skill-tag">CSS <button class="remove-skill"><i class="fas fa-times"></i></button></div>
                                        <div class="skill-tag">JavaScript <button class="remove-skill"><i class="fas fa-times"></i></button></div>
                                        <div class="skill-tag">PHP <button class="remove-skill"><i class="fas fa-times"></i></button></div>
                                        <div class="skill-tag">MySQL <button class="remove-skill"><i class="fas fa-times"></i></button></div>
                                    </div>
                                    
                                    <div class="add-skill-form">
                                        <input type="text" placeholder="Add a new skill">
                                        <button class="add-skill-btn">Add</button>
                                    </div>
                                </div>
                                
                                <!-- Experience Section -->
                                <div class="experience-section">
                                    <h3>Work Experience</h3>
                                    
                                    <div class="experience-item">
                                        <div class="experience-header">
                                            <h4>Web Developer Intern</h4>
                                            <div class="experience-actions">
                                                <button class="edit-experience"><i class="fas fa-edit"></i></button>
                                                <button class="delete-experience"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <p class="company-name">Tech Solutions Inc.</p>
                                        <p class="experience-date">January 2023 - April 2023</p>
                                        <p class="experience-description">Assisted in developing and maintaining company websites. Collaborated with the design team to implement responsive layouts. Participated in code reviews and debugging sessions.</p>
                                    </div>
                                    
                                    <button class="add-experience-btn"><i class="fas fa-plus"></i> Add Experience</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Education Tab -->
                        <div class="tab-pane" id="education">
                            <div class="education-container">
                                <div class="education-item">
                                    <div class="education-header">
                                        <h4>Bachelor of Science in Computer Science</h4>
                                        <div class="education-actions">
                                            <button class="edit-education"><i class="fas fa-edit"></i></button>
                                            <button class="delete-education"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <p class="institution">Batangas State University</p>
                                    <p class="education-date">2020 - Present</p>
                                    <p class="education-description">Focusing on software development and web technologies. Active member of the Computer Science Society.</p>
                                </div>
                                
                                <button class="add-education-btn"><i class="fas fa-plus"></i> Add Education</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding tab pane
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
</body>

</html>