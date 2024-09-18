<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();
    $workingScheduleAddedSuccess = isset($_SESSION['workingScheduleAddedSuccess']) ? $_SESSION['workingScheduleAddedSuccess'] : '';
    $workingScheduleEditedSuccess = isset($_SESSION['workingScheduleEditedSuccess']) ? $_SESSION['workingScheduleEditedSuccess'] : '';
    $workingScheduleDeletionSuccess = isset($_SESSION['deleteSuccess']) ? $_SESSION['deleteSuccess'] : '';
    $workingScheduleDeletionFailed = isset($_SESSION['deleteFailed']) ? $_SESSION['deleteFailed'] : '';
    
    // Get the sort key, filter key, and search query from query parameters
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'workingDate';
    $filterKey = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Filter the workingSchedulesArray based on the filter key
    $filteredSchedules = array_filter($data['workingSchedulesArray'], function($schedule) use ($filterKey) {
        $currentDate = new DateTime(); // Get the current date and time
        $workingDate = new DateTime($schedule['workingDate']); // Convert working date to DateTime object

        switch ($filterKey) {
            case 'today':
                return $workingDate->format('Y-m-d') === $currentDate->format('Y-m-d');
            case 'week':
                return $workingDate->format('W') === $currentDate->format('W') && $workingDate->format('Y') === $currentDate->format('Y');
            case 'month':
                return $workingDate->format('Y-m') === $currentDate->format('Y-m');
            case 'year':
                return $workingDate->format('Y') === $currentDate->format('Y');
            case 'all':
            default:
                return true; // No filter applied
        }
    });

    if(!empty($searchQuery)){
       // Further filter based on search query
        $initialFilteredSchedules = array_filter($filteredSchedules, function($schedule) use ($searchQuery) {
            return stripos($schedule['id'], $searchQuery) !== false ||
                   stripos($schedule['workingDate'], $searchQuery) !== false ||
                   stripos($schedule['workingStartingTime'], $searchQuery) !== false ||
                   stripos($schedule['workingOffTime'], $searchQuery) !== false;
        });

        // Check if the initial filter resulted in any results
        if (empty($initialFilteredSchedules)) {
            // If no results found, search in staffsArray
            $matchedStaffs = array_filter($data['staffsArray'], function($staff) use ($searchQuery) {
                return stripos($staff['username'], $searchQuery) !== false;
            });

            // Extract IDs of the matched staff members
            $matchedStaffIds = array_column($matchedStaffs, 'id');

            // Filter workingSchedulesArray based on the matched staff IDs
            $filteredSchedules = array_filter($filteredSchedules, function($schedule) use ($matchedStaffIds) {
                return in_array($schedule['id'], $matchedStaffIds);
            });
        } else {
            // Use the initial filtered results
            $filteredSchedules = $initialFilteredSchedules;
        }
    }

    // Sorting logic remains unchanged
    if ($sortKey == 'username') {
        usort($data['staffsArray'], function($a, $b) use ($sortKey) {
            return strcmp($a[$sortKey], $b[$sortKey]);
        });
    } else {
        usort($filteredSchedules, function($a, $b) use ($sortKey) {
            return strcmp($a[$sortKey], $b[$sortKey]);
        });
    }
    
    // Function to get the status name by statusID
    function getStatusNameByID($statusArray, $statusID) {
        foreach ($statusArray as $status) {
            if ($status['statusID'] == $statusID) {
                return htmlspecialchars($status['statusName']);
            }
        }
        return 'Unknown';
    }

    // Function to get the color based on statusID
    function getStatusColor($statusID) {
        switch ($statusID) {
            case 1:
                return 'blue';
            case 2:
                return 'green';
            case 3:
                return 'red';
            case 4:
                return 'yellow';
            default:
                return 'black';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            .successSessionMsg{
                color: white; 
                border: 1px solid green; 
                width: 30%; 
                height: 40px;
                background-color: green;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
            .failedSessionMsg{
                color: white; 
                border: 1px solid red; 
                width: 30%; 
                height: 40px;
                background-color: red;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
        </style>
        <?php if (!empty($workingScheduleAddedSuccess)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($workingScheduleAddedSuccess); ?>
            </div>
            <?php unset($_SESSION['workingScheduleAddedSuccess']); // Clear the session message after displaying ?>
        <?php endif; ?>
        <?php if (!empty($workingScheduleEditedSuccess)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($workingScheduleEditedSuccess); ?>
            </div>
            <?php unset($_SESSION['workingScheduleEditedSuccess']); // Clear the session message after displaying ?>
        <?php endif; ?>
        <?php if (!empty($workingScheduleDeletionSuccess)): ?>
                <div class="successSessionMsg">
                    <?php echo htmlspecialchars($workingScheduleDeletionSuccess); ?>
                </div>
                <?php unset($_SESSION['deleteSuccess']); // Clear the session message after displaying ?>
        <?php endif; ?>
        <?php if (!empty($workingScheduleDeletionFailed)): ?>
                <div class="failedSessionMsg">
                    <?php echo htmlspecialchars($workingScheduleDeletionFailed); ?>
                </div>
                <?php unset($_SESSION['deleteFailed']); // Clear the session message after displaying ?>
        <?php endif; ?>
                
        <h3>Working Schedule Table</h3>
        <!-- Filter dropdown -->
        <div>
            <form method="GET" action="" id="filterForm">
                <label for="filter">Filter by:</label>
                <select name="filter" id="filter" onchange="updateUrl()">
                    <option value="all" <?php if ($filterKey === 'all') echo 'selected'; ?>>All</option>
                    <option value="today" <?php if ($filterKey === 'today') echo 'selected'; ?>>Today</option>
                    <option value="week" <?php if ($filterKey === 'week') echo 'selected'; ?>>This Week</option>
                    <option value="month" <?php if ($filterKey === 'month') echo 'selected'; ?>>This Month</option>
                    <option value="year" <?php if ($filterKey === 'year') echo 'selected'; ?>>This Year</option>
                </select>
                
                <!-- Pass the current sort key as a hidden input -->
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </form>
            
            <form id="searchForm">
                <!-- Search Bar -->
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search..." oninput="updateSearchUrl()" />

                <!-- Hidden inputs for sort and filter -->
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterKey); ?>">
            </form>
        </div>
        

        <div class="scrollable-container">
            <table id="workingScheduleTable" class="userManagementTable">
                <thead>
                    <tr>
                        <th colspan="3">Action</th>
                        <th><a href="?controller=user&action=workingScheduleManagement&sort=id&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=workingScheduleManagement&sort=username&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Username &#8597;</a></th>
                        <th><a href="?controller=user&action=workingScheduleManagement&sort=workingDate&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Date &#8597;</a></th>
                        <th><a href="?controller=user&action=workingScheduleManagement&sort=workingStartingTime&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Starting Time &#8597;</a></th>
                        <th><a href="?controller=user&action=workingScheduleManagement&sort=workingOffTime&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Off <br> Time &#8597;</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Determine if there are any working schedules with all required attributes
                    $hasCompleteSchedules = !empty($filteredSchedules);

                    if ($hasCompleteSchedules): ?>
                        <?php foreach ($filteredSchedules as $workingSchedule): ?>
                            <?php
                            $allAttributesPresent = !empty($workingSchedule['id']) && 
                                                    !empty($workingSchedule['workingDate']) && 
                                                    !empty($workingSchedule['workingStartingTime']) && 
                                                    !empty($workingSchedule['workingOffTime']);
                            $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                            ?>

                            <?php if ($allAttributesPresent): ?>
                                <tr>
                                    <td colspan="<?php echo $hasEditPermission ? '1' : '3'; ?>">
                                        <a href="javascript:void(0)" onclick="showWorkingScheduleAttendance(
                                            '<?php echo htmlspecialchars($workingSchedule['id']); ?>',
                                            '<?php echo htmlspecialchars($workingSchedule['workingDate']); ?>',
                                            '<?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?>',
                                            '<?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?>'
                                        )">Details</a>
                                    </td>

                                    <?php if ($hasEditPermission): ?>
                                        <td>
                                            <a href="index.php?controller=user&action=editWorkingSchedule&id=<?php echo htmlspecialchars($workingSchedule['id']); ?>
                                                &workingDate=<?php echo htmlspecialchars($workingSchedule['workingDate']); ?>
                                                &workingStartingTime=<?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?>
                                                &workingOffTime=<?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?>
                                            ">Edit</a>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($hasEditPermission): ?>
                                        <td>
                                            <a href="index.php?controller=user&action=deleteWorkingSchedule&id=<?php echo htmlspecialchars($workingSchedule['id']); ?>
                                                &workingDate=<?php echo htmlspecialchars($workingSchedule['workingDate']); ?>
                                                &workingStartingTime=<?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?>
                                                &workingOffTime=<?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?>
                                            ">Delete</a>
                                        </td>
                                    <?php endif; ?>

                                    <!-- Staff Data -->
                                    <td><?php echo htmlspecialchars($workingSchedule['id']); ?></td>
                                    <td>
                                        <?php
                                        // Find the staff username by matching the staff id with the workingSchedule id
                                        if (!empty($data['staffsArray'])) {
                                            foreach ($data['staffsArray'] as $staff) {
                                                if ($staff['id'] == $workingSchedule['id']) {
                                                    echo htmlspecialchars($staff['username']); // print matched username
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    </td>

                                    <!-- Working Schedule Data -->
                                    <td><?php echo htmlspecialchars($workingSchedule['workingDate']); ?></td>
                                    <td><?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?></td>
                                    <td><?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No working schedules available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

        <?php
        if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['permissions'])) {
            if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                echo '
                <a href="index.php?controller=user&action=createNewWorkSchedule"> + Create A New Work Schedule</a>';
            } 
        } ?>
        

        <!-- Working Schedule Attendance Table -->
        <div class="workingScheduleAttendance" id="workingScheduleAttendance">
            <h3>Working Schedule Attendance Details</h3>
            <table class="userManagementTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Working Date</th>
                        <th>Working Starting Time</th>
                        <th>Working Off Time</th>
                        <th>Photo</th>
                        <th>Location</th>
                        <th>Attendance Date Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="id"></td>
                        <td id="attendanceStatus"></td>
                        <td id="workingDate"></td>
                        <td id="workingStartingTime"></td>
                        <td id="workingOffTime"></td>
                        <td id="photo"></td>
                        <td id="location"></td>
                        <td id="attendanceDateTime"></td>
                    </tr>
                </tbody>
            </table>
        </div>
         
        <script>
            function updateUrl() {
                var filterValue = document.getElementById('filter').value;
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var searchQuery = encodeURIComponent(document.querySelector('input[name="search"]').value);

                // Build the new URL with the selected filter and current sort key
                var newUrl = `?controller=user&action=workingScheduleManagement&sort=${sortKey}&filter=${filterValue}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }

            function updateSearchUrl() {
                var searchQuery = encodeURIComponent(document.getElementById('search').value.trim());
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var filterKey = encodeURIComponent(document.querySelector('input[name="filter"]').value);

                // Build the new URL with the search query, filter, and sort key
                var newUrl = `?controller=user&action=workingScheduleManagement&sort=${sortKey}&filter=${filterKey}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            
            const attendancesArray = <?php echo json_encode($data['attendancesArray']); ?>;
            const attendancesStatusArray = <?php echo json_encode($data['attendancesStatusArray']); ?>;
            function showWorkingScheduleAttendance(id, workingDate, workingStartingTime, workingOffTime) {
                // Find the attendance in the attendancesArray
                const attendanceData = attendancesArray.find(attendance => 
                    attendance.id == id &&
                    attendance.workingDate == workingDate &&
                    attendance.workingStartingTime == workingStartingTime &&
                    attendance.workingOffTime == workingOffTime
                );

                if (attendanceData) {
                    // Show the working schedule attendance table
                    document.getElementById('workingScheduleAttendance').style.display = 'block';

                    // Populate the table with attendance data
                    document.getElementById('id').textContent = attendanceData.id || 'Empty';
                    document.getElementById('workingDate').textContent = attendanceData.workingDate || 'Empty';
                    document.getElementById('workingStartingTime').textContent = attendanceData.workingStartingTime || 'Empty';
                    document.getElementById('workingOffTime').textContent = attendanceData.workingOffTime || 'Empty';
                    document.getElementById('photo').textContent = attendanceData.photo || 'No Photo Provided';
                    document.getElementById('location').textContent = attendanceData.location || 'No Location Provided';
                    document.getElementById('attendanceDateTime').textContent = attendanceData.attendanceDateTime || 'No Attendance Date Time Provided';

                    // Find and display the corresponding attendance status
                    const status = attendancesStatusArray.find(status => status.statusID == attendanceData.statusID);
                    if (status) {
                        const statusColorMap = {
                            1: 'blue',
                            2: 'green',
                            3: 'red',
                            4: 'yellow'
                        };
                        const statusColor = statusColorMap[status.statusID] || 'black';
                        document.getElementById('attendanceStatus').textContent = status.statusName;
                        document.getElementById('attendanceStatus').style.color = statusColor;
                    } else {
                        document.getElementById('attendanceStatus').textContent = 'Unknown';
                    }
                } else {
                    // Hide the table if no matching attendance found
                    document.getElementById('workingScheduleAttendance').style.display = 'none';
                }
            }
        </script>
        
    </body>
</html>
