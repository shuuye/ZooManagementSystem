<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    require_once 'StaffUserManagementCtrl.php';
    require_once __DIR__ . '/../../Model/User/WorkingScheduleModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';
    
    class AttendanceManagementCtrl extends StaffUserManagementCtrl{
        
        public function addDefaultAttendanceToDB($id, $working_date, $working_starting_time, $working_off_time){
            $attendanceModel = new AttendanceModel();
            $attendanceModel->addDefaultAttendanceIntoDB($id, $working_date, $working_starting_time, $working_off_time);
        }
        
        public function removeAttendanceForDeletedWorkingSchedule($id, $working_date, $working_starting_time, $working_off_time){
            $attendanceModel = new AttendanceModel();
            $attendanceModel->removeAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);
        }
        
        public function updateAttendanceStatus() {
            // Get all the parameters from the URL
            $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
            $working_date = isset($_POST['working_date']) ? htmlspecialchars($_POST['working_date']) : null;
            $working_starting_time = isset($_POST['working_starting_time']) ? htmlspecialchars($_POST['working_starting_time']) : null;
            $working_off_time = isset($_POST['working_off_time']) ? htmlspecialchars($_POST['working_off_time']) : null;
            $status_id = isset($_POST['status_id']) ? htmlspecialchars($_POST['status_id']) : null;

            // Check if all necessary parameters are available
            if ($id && $working_date && $working_starting_time && $working_off_time && $status_id) {
                // Call the model function to update the attendance status using the primary key and status_id
                $attendanceModel = new AttendanceModel();
                $updated=true;
                
                if($attendanceModel->isPrimaryKeyExistInAttendanceDB($id, $working_date, $working_starting_time, $working_off_time)){
                    $updated = $attendanceModel->updateStatusIDFromAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time, $status_id);
                }
                if($updated){
                    header("Location: index.php?controller=user&action=attendanceManagement&sort=working_date&filter=week");
                }
                
            } else {
                // Handle the error in case any parameter is missing
                echo "Error: Missing required parameters to update attendance status.";
                exit();
            }
        }
                
        public function editAttendanceStatus() {
            // Existing code
            $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
            $working_date = isset($_GET['working_date']) ? htmlspecialchars($_GET['working_date']) : null;
            $working_starting_time = isset($_GET['working_starting_time']) ? htmlspecialchars($_GET['working_starting_time']) : null;
            $working_off_time = isset($_GET['working_off_time']) ? htmlspecialchars($_GET['working_off_time']) : null;
            $status_id = isset($_GET['status_id']) ? htmlspecialchars($_GET['status_id']) : null;

            $attendanceModel = new AttendanceModel();
            $selectedAttendance = $attendanceModel->getAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);

            $attendanceStatusArray = $this->processAttendanceStatus();

            $data = [
                'pageTitle' => 'Attendance Status Editing Form',
                'selectedAttendance' => $selectedAttendance,
                'attendanceStatusArray' => $attendanceStatusArray['attendancesStatusArray'],
                'action' => 'edit',
            ];

            $view = ['attendanceStatusEditingFromView'];
            $this->renderView($view, $data);
        }

        public function attendanceManagement(){
            //set role for each data
            $attendancesArray = $this->processWorkingAttendance();
            $attendancesStatusArray = $this->processAttendanceStatus();
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Attendance Management Panel');
            $data['attendancesArray'] = $attendancesArray['attendancesArray'];
            $data['attendancesStatusArray'] = $attendancesStatusArray['attendancesStatusArray'];
            $view = ['adminTopNavHeader','userManagementTopNav','staffManagementTopNav','attendanceManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
        }
    }

