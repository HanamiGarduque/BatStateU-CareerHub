<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BatStateU Career Hub - Applications Management</title>
  <link rel="stylesheet" href="../Layouts/employer.css">
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
          <li>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li>
            <a href="employer_profile.php"><i class="fas fa-user-tie"></i> Employer Profile</a>
          </li>
          <li>
            <a href="job_postings.php"><i class="fas fa-briefcase"></i> Job Postings</a>
          </li>
          <li class="active">
            <a href="applications.php"><i class="fas fa-file-alt"></i> Applications</a>
          </li>
          <li>
            <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
          </li>
          <li>
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
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
          <input type="text" placeholder="Search applications...">
        </div>
        <div class="user-menu">
          <div class="notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">5</span>
          </div>
          <div class="user-profile">
            <img src="../placeholder.jpg" alt="Profile Picture">
            <span>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; ?></span>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <h1>Applications Management</h1>
        
        <!-- Applications Filter -->
        <div class="applications-filter">
          <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All Applications (156)</button>
            <button class="filter-tab" data-filter="pending">Pending Review (42)</button>
            <button class="filter-tab" data-filter="shortlisted">Shortlisted (28)</button>
            <button class="filter-tab" data-filter="interview">Interview (35)</button>
            <button class="filter-tab" data-filter="offered">Offered (18)</button>
            <button class="filter-tab" data-filter="rejected">Rejected (33)</button>
          </div>
          
          <div class="filter-options">
            <select class="sort-select">
              <option value="newest">Newest First</option>
              <option value="oldest">Oldest First</option>
              <option value="name-az">Name (A-Z)</option>
              <option value="name-za">Name (Z-A)</option>
              <option value="job-az">Job Title (A-Z)</option>
              <option value="job-za">Job Title (Z-A)</option>
            </select>
          </div>
        </div>
        
        <!-- Applications List -->
        <div class="applications-list">
          <!-- Application Item 1 -->
          <div class="application-item">
            <div class="application-main">
              <div class="candidate-info">
                <img src="../placeholder.jpg" alt="Candidate Photo" class="candidate-photo">
                <div class="candidate-details">
                  <h3 class="candidate-name">John Doe</h3>
                  <p class="candidate-title">Senior Web Developer</p>
                  <div class="application-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i> 5 years experience</div>
                    <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                    <div class="meta-item"><i class="fas fa-graduation-cap"></i> BS Computer Science</div>
                    <div class="meta-item"><i class="fas fa-calendar"></i> Applied on May 15, 2023</div>
                  </div>
                </div>
              </div>
              
              <div class="job-applied">
                <h4>Senior Web Developer</h4>
                <p>Tech Solutions Inc.</p>
                <span class="application-status-badge interview">Interview Scheduled</span>
              </div>
              
              <div class="application-actions">
                <button class="action-btn view-resume-btn"><i class="fas fa-file-pdf"></i> View Resume</button>
                <button class="action-btn change-status-btn"><i class="fas fa-exchange-alt"></i> Change Status</button>
                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
              </div>
            </div>
            
            <div class="application-details-expanded">
              <div class="candidate-profile">
                <div class="profile-section">
                  <h4>Skills</h4>
                  <div class="skills-list">
                    <span class="skill-tag">JavaScript</span>
                    <span class="skill-tag">React</span>
                    <span class="skill-tag">Node.js</span>
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">HTML/CSS</span>
                    <span class="skill-tag">Git</span>
                  </div>
                </div>
                
                <div class="profile-section">
                  <h4>Experience</h4>
                  <div class="experience-item">
                    <h5>Senior Frontend Developer</h5>
                    <p>Web Solutions Co. | 2020 - Present</p>
                    <p>Led the frontend development team in creating responsive web applications using React and Redux. Implemented best practices for code quality and performance optimization.</p>
                  </div>
                  <div class="experience-item">
                    <h5>Web Developer</h5>
                    <p>Digital Creations Inc. | 2018 - 2020</p>
                    <p>Developed and maintained client websites using PHP, JavaScript, and MySQL. Collaborated with designers to implement responsive designs.</p>
                  </div>
                </div>
                
                <div class="profile-section">
                  <h4>Education</h4>
                  <div class="education-item">
                    <h5>BS Computer Science</h5>
                    <p>Batangas State University | 2014 - 2018</p>
                  </div>
                </div>
              </div>
              
              <div class="application-timeline">
                <h4>Application Timeline</h4>
                <div class="timeline">
                  <div class="timeline-item active">
                    <div class="timeline-icon"><i class="fas fa-check"></i></div>
                    <div class="timeline-content">
                      <h4>Application Received</h4>
                      <p>May 15, 2023 at 10:30 AM</p>
                    </div>
                  </div>
                  
                  <div class="timeline-item active">
                    <div class="timeline-icon"><i class="fas fa-check"></i></div>
                    <div class="timeline-content">
                      <h4>Resume Screened</h4>
                      <p>May 16, 2023 at 2:15 PM</p>
                    </div>
                  </div>
                  
                  <div class="timeline-item active">
                    <div class="timeline-icon"><i class="fas fa-check"></i></div>
                    <div class="timeline-content">
                      <h4>Interview Scheduled</h4>
                      <p>May 18, 2023 at 9:45 AM</p>
                      <div class="interview-details">
                        <p><strong>Date:</strong> May 22, 2023</p>
                        <p><strong>Time:</strong> 10:00 AM - 11:30 AM</p>
                        <p><strong>Location:</strong> Online via Zoom</p>
                        <p><strong>Interviewer:</strong> Maria Santos, HR Manager</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="timeline-content">
                      <h4>Technical Interview</h4>
                      <p>Pending</p>
                    </div>
                  </div>
                  
                  <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="timeline-content">
                      <h4>Final Decision</h4>
                      <p>Pending</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="application-notes">
                <h4>Notes</h4>
                <div class="notes-list">
                  <div class="note-item">
                    <div class="note-header">
                      <p class="note-author">Maria Santos, HR Manager</p>
                      <p class="note-date">May 16, 2023 at 2:15 PM</p>
                    </div>
                    <p class="note-content">Strong candidate with relevant experience. Resume shows good progression and skill development. Recommend scheduling an initial interview.</p>
                  </div>
                </div>
                
                <div class="add-note-form">
                  <textarea placeholder="Add a note about this candidate..."></textarea>
                  <button class="add-note-btn">Add Note</button>
                </div>
              </div>
              
              <div class="candidate-actions">
                <button class="candidate-action-btn"><i class="fas fa-envelope"></i> Email Candidate</button>
                <button class="candidate-action-btn"><i class="fas fa-calendar-alt"></i> Schedule Interview</button>
                <button class="candidate-action-btn"><i class="fas fa-user-check"></i> Move to Shortlist</button>
                <button class="candidate-action-btn reject"><i class="fas fa-times"></i> Reject Application</button>
              </div>
            </div>
          </div>
          
          <!-- Application Item 2 -->
          <div class="application-item">
            <div class="application-main">
              <div class="candidate-info">
                <img src="../placeholder.jpg" alt="Candidate Photo" class="candidate-photo">
                <div class="candidate-details">
                  <h3 class="candidate-name">Jane Smith</h3>
                  <p class="candidate-title">Marketing Specialist</p>
                  <div class="application-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i> 3 years experience</div>
                    <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Lipa City</div>
                    <div class="meta-item"><i class="fas fa-graduation-cap"></i> BS Marketing</div>
                    <div class="meta-item"><i class="fas fa-calendar"></i> Applied on May 14, 2023</div>
                  </div>
                </div>
              </div>
              
              <div class="job-applied">
                <h4>Marketing Specialist</h4>
                <p>Global Marketing PH</p>
                <span class="application-status-badge shortlisted">Shortlisted</span>
              </div>
              
              <div class="application-actions">
                <button class="action-btn view-resume-btn"><i class="fas fa-file-pdf"></i> View Resume</button>
                <button class="action-btn change-status-btn"><i class="fas fa-exchange-alt"></i> Change Status</button>
                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
              </div>
            </div>
            
            <div class="application-details-expanded">
              <!-- Similar expanded content as above -->
            </div>
          </div>
          
          <!-- Application Item 3 -->
          <div class="application-item">
            <div class="application-main">
              <div class="candidate-info">
                <img src="../placeholder.jpg" alt="Candidate Photo" class="candidate-photo">
                <div class="candidate-details">
                  <h3 class="candidate-name">Mike Johnson</h3>
                  <p class="candidate-title">Graphic Designer</p>
                  <div class="application-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i> 2 years experience</div>
                    <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Santo Tomas</div>
                    <div class="meta-item"><i class="fas fa-graduation-cap"></i> BFA Graphic Design</div>
                    <div class="meta-item"><i class="fas fa-calendar"></i> Applied on May 12, 2023</div>
                  </div>
                </div>
              </div>
              
              <div class="job-applied">
                <h4>Graphic Designer</h4>
                <p>Creative Designs Co.</p>
                <span class="application-status-badge offered">Offer Sent</span>
              </div>
              
              <div class="application-actions">
                <button class="action-btn view-resume-btn"><i class="fas fa-file-pdf"></i> View Resume</button>
                <button class="action-btn change-status-btn"><i class="fas fa-exchange-alt"></i> Change Status</button>
                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
              </div>
            </div>
            
            <div class="application-details-expanded">
              <!-- Similar expanded content as above -->
            </div>
          </div>
          
          <!-- Application Item 4 -->
          <div class="application-item">
            <div class="application-main">
              <div class="candidate-info">
                <img src="../placeholder.jpg" alt="Candidate Photo" class="candidate-photo">
                <div class="candidate-details">
                  <h3 class="candidate-name">Sarah Garcia</h3>
                  <p class="candidate-title">Customer Service Representative</p>
                  <div class="application-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i> 1 year experience</div>
                    <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Batangas City</div>
                    <div class="meta-item"><i class="fas fa-graduation-cap"></i> BS Business Administration</div>
                    <div class="meta-item"><i class="fas fa-calendar"></i> Applied on May 10, 2023</div>
                  </div>
                </div>
              </div>
              
              <div class="job-applied">
                <h4>Customer Service Representative</h4>
                <p>Support Solutions</p>
                <span class="application-status-badge pending">Pending Review</span>
              </div>
              
              <div class="application-actions">
                <button class="action-btn view-resume-btn"><i class="fas fa-file-pdf"></i> View Resume</button>
                <button class="action-btn change-status-btn"><i class="fas fa-exchange-alt"></i> Change Status</button>
                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
              </div>
            </div>
            
            <div class="application-details-expanded">
              <!-- Similar expanded content as above -->
            </div>
          </div>
          
          <!-- Application Item 5 -->
          <div class="application-item">
            <div class="application-main">
              <div class="candidate-info">
                <img src="../placeholder.jpg" alt="Candidate Photo" class="candidate-photo">
                <div class="candidate-details">
                  <h3 class="candidate-name">Robert Lee</h3>
                  <p class="candidate-title">Data Analyst</p>
                  <div class="application-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i> 4 years experience</div>
                    <div class="meta-item"><i class="fas fa-map-marker-alt"></i> Tanauan</div>
                    <div class="meta-item"><i class="fas fa-graduation-cap"></i> BS Statistics</div>
                    <div class="meta-item"><i class="fas fa-calendar"></i> Applied on May 8, 2023</div>
                  </div>
                </div>
              </div>
              
              <div class="job-applied">
                <h4>Data Analyst</h4>
                <p>Tech Insights Inc.</p>
                <span class="application-status-badge rejected">Rejected</span>
              </div>
              
              <div class="application-actions">
                <button class="action-btn view-resume-btn"><i class="fas fa-file-pdf"></i> View Resume</button>
                <button class="action-btn change-status-btn"><i class="fas fa-exchange-alt"></i> Change Status</button>
                <button class="toggle-details-btn"><i class="fas fa-chevron-down"></i></button>
              </div>
            </div>
            
            <div class="application-details-expanded">
              <!-- Similar expanded content as above -->
            </div>
          </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
          <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
          <button class="pagination-btn active">1</button>
          <button class="pagination-btn">2</button>
          <button class="pagination-btn">3</button>
          <button class="pagination-btn">4</button>
          <button class="pagination-btn">5</button>
          <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Filter tabs functionality
      const filterTabs = document.querySelectorAll('.filter-tab');
      const applicationItems = document.querySelectorAll('.application-item');
      
      filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remove active class from all tabs
          filterTabs.forEach(t => t.classList.remove('active'));
          
          // Add active class to clicked tab
          this.classList.add('active');
          
          // Get filter value
          const filter = this.getAttribute('data-filter');
          
          // Show/hide application items based on filter
          applicationItems.forEach(item => {
            if (filter === 'all') {
              item.style.display = 'block';
            } else {
              const statusBadge = item.querySelector('.application-status-badge');
              if (statusBadge && statusBadge.classList.contains(filter)) {
                item.style.display = 'block';
              } else {
                item.style.display = 'none';
              }
            }
          });
        });
      });
      
      // Toggle application details
      const toggleButtons = document.querySelectorAll('.toggle-details-btn');
      
      toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
          const applicationItem = this.closest('.application-item');
          const detailsSection = applicationItem.querySelector('.application-details-expanded');
          
          if (detailsSection.style.display === 'block') {
            detailsSection.style.display = 'none';
            this.innerHTML = '<i class="fas fa-chevron-down"></i>';
          } else {
            detailsSection.style.display = 'block';
            this.innerHTML = '<i class="fas fa-chevron-up"></i>';
          }
        });
      });
      
      // Change application status
      const changeStatusButtons = document.querySelectorAll('.change-status-btn');
      
      changeStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
          Swal.fire({
            title: 'Change Application Status',
            html: `
              <select id="status-select" class="swal2-select">
                <option value="pending">Pending Review</option>
                <option value="shortlisted">Shortlisted</option>
                <option value="interview">Interview</option>
                <option value="offered">Offer</option>
                <option value="rejected">Rejected</option>
              </select>
            `,
            showCancelButton: true,
            confirmButtonColor: '#c41e3a',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Update Status'
          }).then((result) => {
            if (result.isConfirmed) {
              const status = document.getElementById('status-select').value;
              const statusText = {
                'pending': 'Pending Review',
                'shortlisted': 'Shortlisted',
                'interview': 'Interview Scheduled',
                'offered': 'Offer Sent',
                'rejected': 'Rejected'
              };
              
              const applicationItem = this.closest('.application-item');
              const statusBadge = applicationItem.querySelector('.application-status-badge');
              
              // Remove all status classes
              statusBadge.classList.remove('pending', 'shortlisted', 'interview', 'offered', 'rejected');
              
              // Add new status class
              statusBadge.classList.add(status);
              
              // Update status text
              statusBadge.textContent = statusText[status];
              
              Swal.fire(
                'Updated!',
                'The application status has been updated.',
                'success'
              );
            }
          });
        });
      });
    });
  </script>
</body>

</html>