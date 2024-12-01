<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unverified Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="/cssc/vendor/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/cssc/js/staff/unverified_entries.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Unverified Entries</h1>

        <!-- Table to display unverified entries -->
        <table class="table table-bordered" id="unverifiedEntriesTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Submission Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No unverified entries found.</td>
            </tr>
        </tbody>
        </table>
    </div>

    <!-- Modal for displaying extra details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Entry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>   
                    <p><strong>Student ID:</strong> <span id="modalStudentId"></span></p>
                    <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Course:</strong> <span id="modalCourse"></span></p>
                    <p><strong>Year Level:</strong> <span id="modalYearLevel"></span></p>
                    <p><strong>Section:</strong> <span id="modalSection"></span></p>
                    <p><strong>Adviser Name:</strong> <span id="modalAdviserName"></span></p>
                    <p><strong>GWA:</strong> <span id="modalGWA"></span></p>
                    <p><strong>Image Proof:</strong> <img id="modalImageProof" src="" alt="Image Proof" style="width: 100%; max-height: 200px;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="approveBtn">Approve</button>
                    <button type="button" class="btn btn-danger" id="rejectBtn">Reject</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
