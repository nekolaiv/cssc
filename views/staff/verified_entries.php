<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verified Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="/cssc/js/staff/verified_entries.js"></script> -->
</head>
<body>
    <div class="container mt-4">
        <h1>Verified Entries</h1>

        <!-- Table to display verified entries -->
        <table class="table table-bordered" id="verifiedEntriesTable">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Adviser Name</th>
                    <th>GWA</th>
                    <th>Verification Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Entries will be dynamically populated here by staff/verified_entries.js -->
                <tr>
                    <td colspan="7" class="text-center">No verified entries found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal for displaying extra details -->
    <div class="modal fade" id="verifiedDetailsModal" tabindex="-1" aria-labelledby="verifiedDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifiedDetailsModalLabel">Entry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Student ID:</strong> <span id="modalVerifiedStudentId"></span></p>
                    <p><strong>Full Name:</strong> <span id="modalVerifiedFullName"></span></p>
                    <p><strong>Email:</strong> <span id="modalVerifiedEmail"></span></p>
                    <p><strong>Course:</strong> <span id="modalVerifiedCourse"></span></p>
                    <p><strong>Year Level:</strong> <span id="modalVerifiedYearLevel"></span></p>
                    <p><strong>Section:</strong> <span id="modalVerifiedSection"></span></p>
                    <p><strong>Adviser Name:</strong> <span id="modalVerifiedAdviserName"></span></p>
                    <p><strong>GWA:</strong> <span id="modalVerifiedGWA"></span></p>
                    <p><strong>Image Proof:</strong> <img id="modalVerifiedImageProof" src="" alt="Image Proof" style="width: 100%; max-height: 200px;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="removeVerifiedBtn">Remove Verified Entry</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
