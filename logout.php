<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Set a flag to indicate logout
file_put_contents('logout_flag.txt', '1'); // Create a flag file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <script>
        // Clear any client-side storage
        sessionStorage.clear();
        localStorage.removeItem('examInProgress');
        
        // Show logout message with SweetAlert2
        Swal.fire({
            title: 'Logging Out',
            html: '<div class="flex items-center justify-center"><i class="fas fa-sign-out-alt text-3xl mr-3 text-blue-600"></i> <span>Successfully logged out!</span></div>',
            icon: 'success',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            willClose: () => {
                window.location.href = "index.php";
            },
            customClass: {
                popup: 'rounded-xl',
                title: 'text-xl font-medium text-gray-800',
                htmlContainer: 'text-base text-gray-600'
            }
        });
        
        // Redirect anyway after 2.5 seconds (as a fallback)
        setTimeout(() => {
            window.location.href = "index.php";
        }, 2500);
    </script>
</body>
</html>