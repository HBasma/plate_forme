<?php
// إعداد الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_help_website";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة نموذج تسجيل الطلاب
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_register'])){ 
    // استلام البيانات من النموذج
    $fullName = $_POST['name'];
    $id = $_POST['number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $yearOfBaccalaureate = $_POST['baccalaureate_year'];
    $specialization = $_POST['specialization'];
    $year = $_POST['year'];
    $university = $_POST['university'];
    
    // التحقق مما إذا كان رقم التسجيل موجودًا بالفعل
    $check_sql = "SELECT * FROM students WHERE ID='$id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // إذا كان الرقم موجودًا بالفعل
        echo "<script>alert('You already have an account with this registration number. Please check your registration number or log in.'); window.location.href='index.php';</script>";
    } else {
        // إدراج البيانات في قاعدة البيانات
        $sql = "INSERT INTO students (FullName, ID, email, password, yearOfBaccalaureate, specialization, year, UniversityName)
        VALUES ('$fullName', '$id', '$email', '$password', '$yearOfBaccalaureate', '$specialization', '$year', '$university')";

if ($conn->query($sql) === TRUE) {
    // إعادة توجيه المستخدم إلى صفحة ملفه الشخصي
    header("Location: http://localhost/startup/etudent/profile1.php?number=$id");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
}
// معالجة نموذج تسجيل الأساتذة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['professor_register'])) {
    // استلام البيانات من النموذج
    $fullName = $_POST['prof_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $state = $_POST['province'];
    $specialization = $_POST['specialization'];
    $university = $_POST['university'];
    $phoneNumber = $_POST['phone'];

   // التحقق مما إذا كان البريد الإلكتروني أو رقم الهاتف موجودًا بالفعل
   $check_email_sql = "SELECT * FROM instructors WHERE Email='$email'";
   $check_phone_sql = "SELECT * FROM instructors WHERE PhoneNumber='$phoneNumber'";
   $check_email_result = $conn->query($check_email_sql);
   $check_phone_result = $conn->query($check_phone_sql);

   if ($check_email_result->num_rows > 0) {
       // إذا كان البريد الإلكتروني موجودًا بالفعل
       echo "<script>alert('This email is already registered. Please use a different email or log in.'); window.location.href='index.php';</script>";
   } elseif ($check_phone_result->num_rows > 0) {
       // إذا كان رقم الهاتف موجودًا بالفعل
       echo "<script>alert('This phone number is already registered. Please use a different phone number or log in.'); window.location.href='index.php';</script>";
   } else {
       // إدراج البيانات في قاعدة البيانات
       $sql = "INSERT INTO instructors (FullName, Email, Password, DateOfBirth, Gender, State, Specialization, University, PhoneNumber)
       VALUES ('$fullName', '$email', '$password', '$birthdate', '$gender', '$state', '$specialization', '$university', '$phoneNumber')";
       if ($conn->query($sql) === TRUE) {
        // إعادة توجيه المستخدم إلى صفحة ملفه الشخصي
        header("Location: http://localhost/startup/etudent/profile2.php?phone=$phoneNumber");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    }
}

// عرض معلومات الطالب إذا تم التسجيل بنجاح
if (isset($_GET['profile']) && $_GET['profile'] === 'true' && isset($_GET['number'])) {
    $number = $_GET['number'];
    $sql = "SELECT * FROM students WHERE number='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>Student Profile</h1>";
        echo "<p><strong>Full Name:</strong> " . $row['name'] . "</p>";
        echo "<p><strong>Registration Number:</strong> " . $row['id'] . "</p>";
        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
        echo "<p><strong>Baccalaureate Year:</strong> " . $row['baccalaureate_year'] . "</p>";
        echo "<p><strong>Specialization:</strong> " . $row['specialization'] . "</p>";
        echo "<p><strong>Year:</strong> " . $row['year'] . "</p>";
        echo "<p><strong>University:</strong> " . $row['university'] . "</p>";
    } else {
        echo "No records found";
    }
}

// إغلاق الاتصال
$conn->close();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Sign In</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>
</head>

<body class="is-preload">

    <div id="wrapper">

        <!-- Header -->
        <header id="header">
            <div class="logo">
                <span class="icon fa-gem"></span>
            </div>
            <div class="content">
                <div class="inner">
                    <h1>Name</h1>
                    <p>Registering on the platform will help you obtain more accurate information and you will have your own page where only everything related to your specialty appears</p>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#contact">Student</a></li>
                    <li><a href="#elements">Professor</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main -->
        <div id="main">

            <!-- Student Registration -->
            <article id="contact">
                <h2 class="major">Students</h2>
                <form method="post" action="index.php"> <!-- Form action pointing to same PHP file -->
                    <div class="fields">
                        <div class="field half">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" required />
                        </div>
                        <div class="field half">
                            <label for="number">Registration Number</label>
                            <input type="text" name="number" id="number" required />
                        </div>
                        <div class="field half">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required />
                        </div>
                        <div class="field half">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required />
                        </div>
                        <div class="field half">
                            <label for="baccalaureate_year">Baccalaureate Year</label>
                            <input type="text" name="baccalaureate_year" id="baccalaureate_year" required />
                        </div>
                        <div class="field half">
                            <label for="specialization">Specialization</label>
                            <input type="text" name="specialization" id="specialization" required />
                        </div>
                        <div class="field half">
                            <label for="year">Year</label>
                            <input type="text" name="year" id="year" required />
                        </div>
                        <div class="field half">
                            <label for="university">University</label>
                            <input type="text" name="university" id="university" required />
                        </div>
                        <ul class="actions">
                            <li><input type="submit" name="student_register" value="Register" class="primary" /></li>
                            <li><input type="reset" value="Reset" /></li>
                        </ul>
                    </div>
                </form>
            </article>

            <!-- Professor Registration -->
            <article id="elements">
                <h2 class="major">Professors</h2>
                <section>
                    <h3 class="major">Register</h3>
                    <form method="post" action="index.php"> <!-- Form action pointing to same PHP file -->
                        <div class="fields">
                            <div class="field half">
                                <label for="prof_name">Full Name</label>
                                <input type="text" name="prof_name" id="prof_name" required />
                            </div>
                            <div class="field half">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required />
                            </div>
                            <div class="field half">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" required />
                            </div>
                            <div class="field half">
                                <label for="birthdate">Date of Birth</label>
                                <input type="date" name="birthdate" id="birthdate" required />
                            </div>
                            <div class="field half">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="field half">
                                <label for="province">Province</label>
                                <input type="text" name="province" id="province" required />
                            </div>
                            <div class="field half">
                                <label for="specialization">Specialization</label>
                                <input type="text" name="specialization" id="specialization" required />
                            </div>
                            <div class="field half">
                                <label for="university">University</label>
                                <input type="text" name="university" id="university" required />
                            </div>
                            <div class="field half">
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" required />
                            </div>
                            <ul class="actions">
                                <li><input type="submit" name="professor_register" value="Register" class="primary" /></li>
                                <li><input type="reset" value="Reset" /></li>
                            </ul>
                        </div>
                    </form>
                </section>
            </article>

        </div>

        <!-- Footer -->
        <footer id="footer">
            <p class="copyright">&copy; alger <a href="site">site</a>.</p>
        </footer>

    </div>

    <!-- BG -->
    <div id="bg"></div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>
