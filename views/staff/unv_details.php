  <!-- Modal for displaying extra details -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Entry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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