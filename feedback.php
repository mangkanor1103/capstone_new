<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="css/font.css"/>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            color: #333;
            font-family: 'Roboto', sans-serif;
            overflow: hidden; /* Prevent body scrolling */
            height: 100vh; /* Full height */
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #28a745; /* Green background */
            color: white;
            padding: 10px 20px; /* Padding for header */
            display: flex;
            justify-content: space-between; /* Space between title and button */
            align-items: center; /* Center items vertically */
            position: fixed;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .header h1 {
            margin: 0; /* Remove default margin */
            font-size: 24px; /* Font size for title */
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px); /* Adjust height to account for header */
            overflow-y: auto; /* Allow scrolling for the container */
            padding-top: 60px; /* Space for fixed header */
        }
        .panel {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 20px;
            width: 100%;
            max-width: 800px; /* Increased width for two columns */
            animation: fadeIn 0.5s ease-in-out; /* Animation for panel */
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #28a745; /* Green border */
            transition: border-color 0.3s, box-shadow 0.3s; /* Transition for input focus */
        }
        .form-control:focus {
            border-color: #218838; /* Darker green on focus */
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
        .btn-danger {
            background-color: #28a745; /* Green button */
            border: none;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s, transform 0.3s; /* Transition for button hover */
        }
        .btn-danger:hover {
            background-color: #218838; /* Darker green on hover */
            transform: translateY(-2px); /* Lift effect on hover */
        }
        .footer {
            background-color: #28a745; /* Green footer */
            color: white;
            padding: 10px 0;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
        }
        .footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s; /* Transition for footer links */
        }
        .footer a:hover {
            color: #00c6ff; /* Change color on hover */
        }
    </style>
</head>

<body>

    <div class="header">
        <h1> RS Entrance Exam</h1>
        <div>
            <?php
            include_once 'dbConnection.php';
            session_start();
            if (!isset($_SESSION['email'])) {
                echo '<a href="#" class="btn btn-light" data-toggle="modal" data-target="#myModal" style="color: green;">Login</a>';
            } else {
                echo '<a href="logout.php?q=feedback.php" class="btn btn-light" style="color: green;">Logout</a>';
            }
            ?>
            <a href="index.php" class="btn btn-light" style="color: green;">Home</a>
        </div>
    </div>

    <div class="container">
        <div class="panel">
            <h2 class="text-center" style="color: #28a745;">Feedback / Report a Problem</h2>
            <div style="font-size: 14px;">
                <?php if (@$_GET['q']) echo '<span style="font-size: 18px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;' . @$_GET['q'] . '</span>';
                else { ?>
                    <form method="post" action="feed.php?q=feedback.php">
                        <div class="form-group">
                            <label for="name"><b>Name:</b></label>
                            <input id="name" name="name" placeholder="Enter your name" class="form-control" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="subject"><b>Subject:</b></label>
                            <input id="subject" name="subject" placeholder="Enter subject" class="form-control" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><b>E-Mail address:</b></label>
                            <input id="email" name="email" placeholder="Enter your email-id" class="form-control" type="email" required>
                        </div>
                        <div class="form-group">
                            <textarea rows="5" name="feedback" class="form-control" placeholder="Write feedback here..." required></textarea>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" name="submit" value="Submit" class="btn btn-danger">
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="row">
            <div class="col-md-4">
                <a href="#" data-toggle="modal" data-target="#login">Admin Login</a>
            </div>
            <div class="col-md-4">
                <a href="#" data-toggle="modal" data-target="#developers">Developers</a>
            </div>
            <div class="col-md-4">
                <a href="feedback.php" target="_blank">Feedback</a>
            </div>
        </div>
    </div>

    <!-- Modal for Developers -->
    <div class="modal fade" id="developers">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28a745; color: white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Developers</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="image/CAM00121.jpg" width=100 height=100 alt="Developer" class="img-rounded">
                        </div>
                        <div class="col-md-8">
                            <a style="color: #28a745; font-size: 18px; text-decoration: none;">Kian A. Rodrigez</a>
                            <h4 style="color: #28a745;">+917785068889</h4>
                            <h4 style="color: #28a745;">kianr664@gmail.com</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Admin Login -->
    <div class="modal fade" id="login">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28a745; color: white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Admin Login</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin.php?q=index.php">
                        <div class="form-group">
                            <input type="text" name="uname" maxlength="20" placeholder="Admin Email" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" maxlength="15" placeholder="Password" class="form-control" required/>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" name="login" value="Login" class="btn btn-danger" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>