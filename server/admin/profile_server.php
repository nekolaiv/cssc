<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once '../../classes/_admin.class.php';
require_once '../../tools/clean.function.php';

// session_start();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $admin = new Admin();

    if (!isset($_SESSION['user-id'])) {
        error_log('admin-profile-handler.php: User ID is not set in session.');
        $response['message'] = 'User is not logged in.';
        echo json_encode($response);
        exit;
    }
    

    $adminId = intval($_SESSION['user-id']);

    try {
        switch ($action) {
            case 'fetchProfile':
                // Fetch admin profile details
                $profile = $admin->getAdminProfile($adminId);
                if ($profile) {
                    $response['success'] = true;
                    $response['data'] = $profile;
                } else {
                    $response['message'] = 'Failed to fetch profile.';
                }
                break;

            case 'updateProfile':
                // Update profile details
                $data = [
                    'identifier' => cleanInput($_POST['identifier'] ?? ''),
                    'firstname' => cleanInput($_POST['firstname'] ?? ''),
                    'middlename' => cleanInput($_POST['middlename'] ?? ''),
                    'lastname' => cleanInput($_POST['lastname'] ?? ''),
                    'email' => cleanInput($_POST['email'] ?? ''),
                    'username' => cleanInput($_POST['username'] ?? '')
                ];

                if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['username'])) {
                    $response['message'] = 'All fields except Middle Name are required.';
                    echo json_encode($response);
                    exit;
                }

                $result = $admin->updateAdminProfile($adminId, $data);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Profile updated successfully.';
                } else {
                    $response['message'] = 'Failed to update profile.';
                }
                break;

            case 'changePassword':
                // Change admin password
                $currentPassword = cleanInput($_POST['currentPassword'] ?? '');
                $newPassword = cleanInput($_POST['newPassword'] ?? '');
                $confirmPassword = cleanInput($_POST['confirmPassword'] ?? '');

                if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                    $response['message'] = 'All password fields are required.';
                    echo json_encode($response);
                    exit;
                }

                if ($newPassword !== $confirmPassword) {
                    $response['message'] = 'New password and confirmation do not match.';
                    echo json_encode($response);
                    exit;
                }

                $result = $admin->changeAdminPassword($adminId, $currentPassword, $newPassword);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Password changed successfully.';
                } else {
                    $response['message'] = 'Failed to change password. Current password might be incorrect.';
                }
                break;

            default:
                $response['message'] = 'Invalid action.';
        }
    } catch (Exception $e) {
        $response['message'] = 'An error occurred: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
