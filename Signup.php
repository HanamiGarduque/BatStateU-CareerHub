<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub: Bridging Education and Employment for Economic Growth</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="Layouts/signup.css">
</head>

<body>
    <?php
    if (isset($_SESSION['status'])) {
        $status = $_SESSION['status'];
        $message = $_SESSION['message'];

        if ($status === 'success') {
            echo "<script>
                Swal.fire({
                    icon: '$status',
                    title: 'Success',
                    text: '$message',
                    confirmButtonText: 'Go to Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: '$status',
                    title: 'Error',
                    text: '$message'
                });
            </script>";
        }

        unset($_SESSION['status']);
        unset($_SESSION['message']);
    }
    ?>

    <div class="container">
        <div class="left-side">
            <div class="logo"></div>
            <h2>Create Account</h2>
            <p>Join our community and start your journey</p>

            <form method="POST" action="">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" value="<?php echo isset($_POST['first_name']) ? strtoupper($_POST['first_name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" value="<?php echo isset($_POST['last_name']) ? strtoupper($_POST['last_name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Address:</label>
                    <input type="text" name="address" value="<?php echo isset($_POST['address']) ? strtoupper($_POST['address']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number: <span class="hint">11 digits</span></label>
                    <input type="text" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password: <span class="hint">minimum of 8 characters, at least 1 uppercase and a number</span></label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit">Register</button>
            </form>

            <div class="signin">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>

        <div class="right-side"></div>
    </div>
</body>

<?php
require_once './Database/db_connections.php';
require_once './Database/crud_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize database getConnection
    $database = new Database();
    $db = $database->getConnect();
    // Initialize the Users object
    $user = new Users($db);

    //sanitize form data
    $first_name = strtoupper(htmlspecialchars(trim($_POST['first_name'])));
    $last_name = strtoupper(htmlspecialchars(trim($_POST['last_name'])));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = strtoupper(htmlspecialchars(trim($_POST['address'])));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Passwords do not match. Please try again.';
        header("Location: Signup.php");
        exit();
    }

    //checks if there are empty fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($phone_number) || empty($password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'All fields are required.';
        header("Location: Signup.php");
        exit();
    }
    //checks if email format is correct
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@g.batstate-u.edu.ph')) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid email format or domain not allowed.';
        header("Location: Signup.php");
        exit();
    }

    //checks password input
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Password must be at least 8 characters long, contain at least one uppercase letter and one number.';
        header("Location: Signup.php");
        exit();
    }
    //checks if the phone number entered contains 11 digits
    if (!preg_match("/^\d{11}$/", $phone_number)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Phone number must be 11 digits.';
        header("Location: Signup.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $user->password = $hashed_password;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->email = $email;
    $user->address = $address;
    $user->phone_number = $phone_number;

    // Check for duplicate accounts
    if ($user->checkDuplicateAcc()) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Username or email already exists.';
    } else {
        // Create a new user
        if ($user->create()) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Your account has been created successfully.';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'There was an error creating your account. Please try again.';
        }
    }

    header("Location: Signup.php");
    exit();
}
?>