<?php
    /*Author Name: Chew Wei Seng*/
    require_once 'UserDecorator.php';

    class AdminReadDecorator extends UserDecorator {
        protected $adminDetails;
        
        public function __construct(UserInterface $user) {
            parent::__construct($user);
            $this->setAdminUserDetails();
            
        }
                        
        private function setAdminUserDetails() {
            // Get details from the previous decorator
            $details = parent::getCurrentUserDetails();
            // Add the 'read' permission
            $details['permissions'][] = 'read';
            // Store the updated details
            $this->adminDetails = $details;
        }

        public function getCurrentUserDetails() {
            return $this->adminDetails; // Return the stored user details
        }
    }
?>
