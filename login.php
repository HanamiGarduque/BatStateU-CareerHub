<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="Layouts/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="left-side"></div>
        <div class="right-side">
            <h1>Welcome Back!</h1>
            <p>Login to your account to continue</p>
            <form action="" method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">Sign In</button>
            </form>
            <div class="signup">
                Don't have an account? <a href="Signup.php">Sign Up</a>
            </div>
        </div>
    </div>


    <?php
    require_once './Database/db_connections.php';
    require_once './Database/crud_functions.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inputEmail = trim($_POST['email']);
        $inputPassword = trim($_POST['password']);

        $database = new Database();
        $db = $database->getConnect();

        $user = new Users($db);
        $stmt = $user->retrieveProfileByEmail($inputEmail);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];
            $role = $row['roles'];
            $status = trim($row['status']);

            if ($status === 'suspended') {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Account Suspended",
                    text: "Your account is suspended. Please contact the admin.",
                });
                </script>';
                exit;
            }

            if (password_verify($inputPassword, $hashedPassword)) {
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $row['first_name'];
                

                if ($role === 'jobseeker') {
                    header("Location: JobSeeker/homepage.php");
                } elseif ($role === 'employer') {   
                    header("Location: Employer/dashboard.php");
                } else {
                    header("Location: Admin/homepage.php");
                }
                exit;
            } else {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Invalid Credentials",
                    text: "Incorrect password. Please try again."
                });
                </script>';
            }
        } else {
            echo '<script>
        Swal.fire({
            icon: "warning",
            title: "No Account Found",
            text: "It looks like you don\'t have an account yet.",
            showCancelButton: true,
            confirmButtonText: "Register Now",
            cancelButtonText: "Close",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "Signup.php";
            }
        });
        </script>';
        }
    
    }

    ?>

</body>

</html>