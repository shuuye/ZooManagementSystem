<?php
/*Author name: Vanness Chaw Jun Kit*/

require_once __DIR__ . '/../../Model/Tickets/TicketPaymentFacade.php';
require_once __DIR__ . '/../../View/TicketView/CustomerTicketView.php';
require_once __DIR__ . '/../../Model/XmlGenerator.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CustomerTicketControl {

    private $model;

    public function __construct() {
        $this->model = new CustomerTicketModel(); // Initialize the model
        $this->facade = new TicketPaymentFacade(); // Initialize the facade
    }

    public function route() {
        $tickets = $this->model->getAvailableTickets();
        $errorMessage = '';

        // Generate a CSRF token
        $csrfToken = bin2hex(random_bytes(32)); // Create a random CSRF token
        $_SESSION['csrf_token'] = $csrfToken;   // Store the CSRF token in the session
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorMessage = $this->processPurchase($tickets);
        }

        CustomerTicketView::displayTickets($tickets, $errorMessage, $csrfToken);
    }

    private function processPurchase($tickets) {
        $errorMessage = '';

        if (isset($_POST['visit_date'])) {
            $visitDate = $_POST['visit_date'];
            $quantities = $_POST['quantity'];

            if (!isset($_SESSION['currentUserModel']) || !isset($_SESSION['currentUserModel']['id'])) {
                return 'User is not logged in. Please log in and try again.';
            }

            $userId = $_SESSION['currentUserModel']['id'];

            // Validate quantities: must be greater than 0
            $validQuantities = false;
            foreach ($quantities as $quantity) {
                if ($quantity > 0 || !is_numeric($quantity)) {
                    $validQuantities = true;
                    break;
                }
            }

            // Validate visit date: must be greater than today
            $today = date('Y-m-d');
            if ($visitDate <= $today) {
                return 'Visit date must be after today date.';
            }

            if ($validQuantities) {
                try {
                    // Using the Facade to handle the purchase process
                    $facade = new TicketPaymentFacade();
                    $facade->purchaseTickets($userId, $visitDate, $quantities);

                    // After successful purchase
                    echo '<script>window.location.href = "paymentPage.php";</script>';
                    exit;
                } catch (Exception $e) {
                    return 'Error processing your purchase. Please try again.';
                }
            } else {
                return 'Invalid quantities. Please check your quantities and try again.';
            }
        }

        return $errorMessage;
    }
}

?>
