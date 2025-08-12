<?php
// view_warning_images.php - Admin page to view all warning images
session_start();
require_once 'dbConnection.php';

// Simple admin check (you can enhance this based on your admin system)
$isAdmin = false;
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $isAdmin = true;
}

// For demo purposes, allow access if user email contains 'admin' or specific admin emails
if (!$isAdmin && isset($_SESSION['email'])) {
    $adminEmails = ['admin@admin.com', 'sunnygkp10@gmail.com']; // Add your admin emails here
    if (in_array($_SESSION['email'], $adminEmails) || strpos($_SESSION['email'], 'admin') !== false) {
        $isAdmin = true;
    }
}

if (!$isAdmin) {
    header("location:index.php");
    exit();
}

// Handle image display request
if (isset($_GET['display_image']) && isset($_GET['id'])) {
    $image_id = intval($_GET['id']);
    $stmt = $con->prepare("SELECT image_data, image_name FROM warning_images WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        header('Content-Type: image/jpeg');
        header('Content-Length: ' . strlen($row['image_data']));
        header('Content-Disposition: inline; filename="' . $row['image_name'] . '"');
        echo $row['image_data'];
    } else {
        header('HTTP/1.0 404 Not Found');
        echo 'Image not found';
    }
    exit();
}

// Get filter parameters
$filter_email = isset($_GET['filter_email']) ? mysqli_real_escape_string($con, $_GET['filter_email']) : '';
$filter_level = isset($_GET['filter_level']) ? mysqli_real_escape_string($con, $_GET['filter_level']) : '';
$filter_date = isset($_GET['filter_date']) ? mysqli_real_escape_string($con, $_GET['filter_date']) : '';

// Build query with filters
$where_conditions = [];
$params = [];
$param_types = '';

if (!empty($filter_email)) {
    $where_conditions[] = "(email LIKE ? OR name LIKE ?)";
    $params[] = "%$filter_email%";
    $params[] = "%$filter_email%";
    $param_types .= 'ss';
}

if (!empty($filter_level)) {
    $where_conditions[] = "violation_level = ?";
    $params[] = $filter_level;
    $param_types .= 's';
}

if (!empty($filter_date)) {
    $where_conditions[] = "DATE(timestamp) = ?";
    $params[] = $filter_date;
    $param_types .= 's';
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
}

// Get warning images with user details
$query = "SELECT wi.*, u.college, u.mob 
          FROM warning_images wi 
          LEFT JOIN user u ON wi.email = u.email 
          $where_clause 
          ORDER BY wi.timestamp DESC";

$stmt = $con->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Warning Images Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .modal { display: none; }
        .modal.active { display: flex !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-shield-exclamation text-red-600 mr-3"></i>
                        Warning Images Dashboard
                    </h1>
                    <p class="text-gray-600 mt-2">Monitor and review security violations</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Admin: <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Administrator'; ?></p>
                    <a href="admin.php" class="text-blue-600 hover:text-blue-800 text-sm">← Back to Admin Panel</a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-filter mr-2"></i>Filters
            </h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Email/Name</label>
                    <input type="text" name="filter_email" value="<?php echo htmlspecialchars($filter_email); ?>" 
                           placeholder="Search by email or name..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Violation Level</label>
                    <select name="filter_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Levels</option>
                        <option value="CRITICAL" <?php echo $filter_level === 'CRITICAL' ? 'selected' : ''; ?>>Critical</option>
                        <option value="SEVERE" <?php echo $filter_level === 'SEVERE' ? 'selected' : ''; ?>>Severe</option>
                        <option value="MAJOR" <?php echo $filter_level === 'MAJOR' ? 'selected' : ''; ?>>Major</option>
                        <option value="MODERATE" <?php echo $filter_level === 'MODERATE' ? 'selected' : ''; ?>>Moderate</option>
                        <option value="MINOR" <?php echo $filter_level === 'MINOR' ? 'selected' : ''; ?>>Minor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Warning Images Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Image -->
                    <div class="relative">
                        <img src="?display_image=1&id=<?php echo $row['id']; ?>" 
                             alt="Warning Image" 
                             class="w-full h-48 object-cover cursor-pointer"
                             onclick="openModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['email']); ?>')">
                        
                        <!-- Violation Level Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                <?php 
                                switch($row['violation_level']) {
                                    case 'CRITICAL': echo 'bg-red-600 text-white'; break;
                                    case 'SEVERE': echo 'bg-red-500 text-white'; break;
                                    case 'MAJOR': echo 'bg-orange-500 text-white'; break;
                                    case 'MODERATE': echo 'bg-yellow-500 text-white'; break;
                                    default: echo 'bg-blue-500 text-white';
                                }
                                ?>">
                                <?php echo $row['violation_level']; ?>
                            </span>
                        </div>

                        <!-- Violation Count -->
                        <div class="absolute top-2 left-2">
                            <span class="bg-black bg-opacity-75 text-white px-2 py-1 text-xs rounded-full">
                                #<?php echo $row['violation_count']; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Student Info -->
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 truncate"><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($row['email']); ?></p>
                        <?php if (!empty($row['college'])): ?>
                            <p class="text-xs text-gray-500 truncate"><?php echo htmlspecialchars($row['college']); ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-3 flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fas fa-clock mr-1"></i><?php echo date('M j, Y', strtotime($row['timestamp'])); ?></span>
                            <span><i class="fas fa-camera mr-1"></i>IMG-<?php echo $row['id']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-camera-slash text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Warning Images Found</h3>
                <p class="text-gray-500">No violation images match your current filters.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
            <div class="p-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold" id="modalTitle">Violation Details</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="Warning Image" class="w-full max-h-96 object-contain">
                <div id="modalDetails" class="mt-4 text-sm text-gray-600"></div>
            </div>
        </div>
    </div>

    <script>
        function openModal(imageId, studentName, studentEmail) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDetails = document.getElementById('modalDetails');
            
            modalImage.src = `?display_image=1&id=${imageId}`;
            modalTitle.textContent = `${studentName} - Security Violation`;
            modalDetails.innerHTML = `
                <p><strong>Student:</strong> ${studentName}</p>
                <p><strong>Email:</strong> ${studentEmail}</p>
                <p><strong>Image ID:</strong> ${imageId}</p>
            `;
            
            modal.classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('imageModal').classList.remove('active');
        }
        
        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
