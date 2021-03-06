<?php
include("../../config.php");
session_start();
$userID = $_SESSION['userID'];

$username = $email_address = $password = $fullname = $mobilenumber = $address = "";

$usernameErr = $passwordErr = "";
$headerModal = "User details changed!";

function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$getUserDetails = mysqli_query($db, "SELECT username, email_address, password, fullName, mobilenumber, address FROM users WHERE userID='$userID'");
if (mysqli_num_rows($getUserDetails) > 0) {
    while ($row = mysqli_fetch_assoc($getUserDetails)) {

        $username = $row["username"];
        $email_address = $row["email_address"];
        $password = $row["password"];
        $fullname = $row["fullName"];
        $mobilenumber = $row["mobilenumber"];
        $address = $row["address"];

    }
}

$check = mysqli_query($db, "SELECT username FROM users WHERE username=(('$username'))");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
        // two passwords are matching
        if ($_POST['password'] == $_POST['confirmpassword']) {
            //cant have an already existing username
            if (mysqli_num_rows($check) > 0) {
                $username = validate_input(($_POST['username']));
                $email_address = validate_input(($_POST['email_address']));
                $password = md5($_POST['password']);// md5 hash passord security
                $fullname = validate_input(($_POST['fullname']));
                $mobilenumber = validate_input(($_POST['mobilenumber']));
                $address = validate_input(($_POST['address']));

                //if query is successful, redirect to signup.php page, done!

                mysqli_query($db, "UPDATE users SET username='$username', email_address='$email_address', password='$password', fullname='$fullname', mobilenumber='$mobilenumber', address='$address' WHERE userID='$userID'");
                include 'SellerChangeDetailsEmail.php';
                $usernameErr = "<div id='button' class='button'> 
		<a class='buttontext' href='../SellerProfile/SellerProfile.php'>Back to Seller Profile</a>
	  </div>";
                include 'modalSellerChangeDetails.php';
                //	echo "Changes Saved!";
            } else {
                $usernameErr = "Username already exists! <br>";
                $headerModal = "Woops! You haven't filled in your details correctly!";
                include 'modalSellerChangeDetails.php';
            }

        } else {
            $passwordErr = "Passwords do not match! <br>";
            $headerModal = "Woops! You haven't filled in your details correctly!";
            include 'modalSellerChangeDetails.php';
        }
    } else {
        echo "submit not working";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Details</title>
    <link rel="stylesheet" href="CreateNewAuctionItem1.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:400,300'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!--[if lt IE 9]>
    <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand">Auction Website</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="../SellerProfile/SellerProfile.php"><span class="glyphicon glyphicon-user"></span>
                    My Account</a></li>
            <li><a href="../../logout.php" class="active"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div id="login">
    <h2><strong><br>Update Account Details:</strong></h2>
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
          enctype="miltipart/form-data" autocomplete="off">
        <fieldset>
            <h3>Full Name: </h3>
            <p><input type="text" value="<?php echo $fullname; ?>" name="fullname" required></p>
            <h3>Username: </h3>
            <p><input type="text" value="<?php echo $username; ?>" name="username"></p>
            <!-- JS because of IE support; better: placeholder="Username" -->
            <h3><strong>Change Password:</h3>
            <p><input type="password" required placeholder="Please a new/old password" name="password"
                      onBlur="if(this.value=='')this.value='Password'"
                      onFocus="if(this.value=='Password')this.value='' " required></p>

            <h3><strong>Re-enter Password:</h3>
            <p><input type="password" required placeholder="Re-enter your new/old password" name="confirmpassword"
                      onBlur="if(this.value=='')this.value='Password'"
                      onFocus="if(this.value=='Password')this.value='' " required></p>

            <!-- JS because of IE support; better: placeholder="Password" -->

            <h3>Email Address: </h3>
            <p><input type="text" value="<?php echo $email_address; ?>" name="email_address" required></p>
            <h3>Phone Number:</h3>
            <p><input type="text" value="<?php echo $mobilenumber; ?>" name="mobilenumber" required></p>
            <h3>Postal Address:</h3>
            <p><input type="text" value="<?php echo $address; ?>" name="address" required></p>
        </fieldset>
    </form>
    <p><br>
        <input type="submit" name="submit" id="submit" class="button" value="Change Details">
        <br>
    </p>
</div>
</body>
</html>

