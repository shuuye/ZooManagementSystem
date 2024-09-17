<?php
    require_once __DIR__ . '/../User/RolesModel.php';
    
    require_once __DIR__ . '/../User/UserModel.php';
    require_once 'UserInterface.php';

    class User extends UserModel implements UserInterface {
        protected $id;
        protected $username;
        protected $password;
        protected $fullName;
        protected $phoneNumber;
        protected $email;
        protected $registrationDateTime;
        protected $lastLoginDateTime;
        protected $lastLogOutDateTime;
        protected $role;

        public function __construct($user = null, $role = null) {
            parent::__construct(); // Initialize the database connection
        
            if ($user !== null && $role !== null) {
                $this->setUserDetails($user, $role); // Load user data if an ID is provided
            }
        }

        protected function isUserDetailsExist($value, $columnName) {
            $isExist = $this->isExistInUserDB($value, $columnName);
            
            return $isExist;
        }

        private function setUserDetails($user, $role) {
            // Set user details
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->fullName = $user['fullName'];
            $this->phoneNumber = $user['phoneNumber'];
            $this->email = $user['email'];
            $this->registrationDateTime = $user['registrationDateTime'];
            $this->lastLoginDateTime = $user['lastLoginDateTime'];
            $this->lastLogOutDateTime = $user['lastLogOutDateTime'];

            // Set role details
            if ($role) {
                $roleSetting = new RolesModel();
                $roleSetting->setRoleID($role['roleID']);
                $roleSetting->setRoleName($role['roleName']);
                $this->role = $roleSetting;
            } else {
                $this->roleID = null;
                $this->roleName = null;
            }
        }
        
        // Authenticate a user by checking the username and password
        public function authUser($username, $password) {
            $user = $this->authUserInDB($username, $password);
            if ($user != false) {
                // Fetch role details
                $rolesModel = new RolesModel();
                $role = $rolesModel->getRoleByID($user['roleID']);

                // Set user and role details using the private method
                $this->setUserDetails($user, $role);
                $this->updateLastLoginDateTimeToDB($user['username']);
                return true;
            }

            return false;
        }
        
        protected function setLatestNewUser(){
            // Get the ID of the newly inserted user
            // Fetch the user details
            $user = $this->getUserDetailsByID($this->getLatestNewUserID());

            // Fetch the role details
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($user['roleID']);

            // Set user and role details using the private method
            $this->setUserDetails($user, $role);

            // Return the full user details
            return $this->getCurrentUserDetails();
        }

        private function addNewUserRoleDetails($userDetails){
            switch ($userDetails['role']['roleID']) {
                case '1':
                    $this->addAdminIntoDB($userDetails['id']);
                    break;
                case '2':
                    $this->addCustomerIntoDB($userDetails['id']);
                    break;
                default:
                    break;
            }
        }

        // Register a new user in the database
        public function addNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID) {
            $createNewStatus = $this->addNewUserIntoDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID);
            echo $createNewStatus . "hi";
            exit();
            if ($createNewStatus) {
                $latestNewUser = $this->setLatestNewUser();
                $this->addNewUserRoleDetails($latestNewUser);
                
                return $latestNewUser;
            }
            return false;
        }
        
        public function getCurrentUserDetails() {
            if ($this->id) { // Check if the user is logged in
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'password' => $this->password,
                    'fullName' => $this->fullName,
                    'phoneNumber' => $this->phoneNumber,
                    'email' => $this->email,
                    'registrationDateTime' => $this->registrationDateTime,
                    'lastLoginDateTime' => $this->lastLoginDateTime,
                    'lastLogOutDateTime' => $this->lastLogOutDateTime,
                    'role' => [
                        'roleID' => $this->role ? $this->role->getRoleID() : null,
                        'roleName' => $this->role ? $this->role->getRoleName() : null
                    ]
                ];
            }

            return null; // No user is logged in
        }
        
        public function getId() {
            return $this->id;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getFullName() {
            return $this->fullName;
        }

        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getRegistrationDateTime() {
            return $this->registrationDateTime;
        }

        public function getLastLoginDateTime() {
            return $this->lastLoginDateTime;
        }
        
        public function getRole(){
            return $this->role;
        }

        public function getRoleByRoleID($roleID) {
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($roleID);
            
            return $role;
        }
        
        public function setId($id): void {
            $this->id = $id;
        }

        public function setUsername($username): void {
            $this->username = $username;
        }

        public function setPassword($password): void {
            $this->password = $password;
        }

        public function setFullName($fullName): void {
            $this->fullName = $fullName;
        }

        public function setPhoneNumber($phoneNumber): void {
            $this->phoneNumber = $phoneNumber;
        }

        public function setEmail($email): void {
            $this->email = $email;
        }

        public function setRegistrationDateTime($registrationDateTime): void {
            $this->registrationDateTime = $registrationDateTime;
        }

        public function setLastLoginDateTime($lastLoginDateTime): void {
            $this->lastLoginDateTime = $lastLoginDateTime;
        }
        
        public function setRole($role): void {
            $this->role = $role;
        }

        public function setRoleByRoleID($roleID): void {
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($roleID);
            
            $this->role = $role;
        }

    }           
    
?>

