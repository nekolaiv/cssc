<?php
require_once '../../classes/_staff.class.php';
require_once '../../tools/clean.function.php';

// session_start();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = cleanInput($_POST['action'] ?? '');
    $staff = new Staff();

    if (!isset($_SESSION['user-id'])) {
        $response['message'] = 'User is not logged in.';
        echo json_encode($response);
        exit;
    }

    $staffId = intval($_SESSION['user-id']);

    try {
        switch ($action) {
            case 'fetchProfile':
                // Fetch staff profile details
                $profile = $staff->getStaffProfile($staffId);
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
                    'username' => cleanInput($_POST['username'] ?? ''),
                    'department_id' => intval(cleanInput($_POST['department'] ?? 0))
                ];

                if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['username'])) {
                    $response['message'] = 'All fields except Middle Name are required.';
                    echo json_encode($response);
                    exit;
                }

                $result = $staff->updateStaffProfile($staffId, $data);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Profile updated successfully.';
                } else {
                    $response['message'] = 'Failed to update profile.';
                }
                break;

            case 'fetchDepartments':
                // Fetch all departments
                $departments = $staff->getDepartments();
                if ($departments) {
                    $response['success'] = true;
                    $response['data'] = $departments;
                } else {
                    $response['message'] = 'Failed to fetch departments.';
                }
                break;

            case 'changePassword':
                // Change staff password
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

                $result = $staff->changeStaffPassword($staffId, $currentPassword, $newPassword);

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
