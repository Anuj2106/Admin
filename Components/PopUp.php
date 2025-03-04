<!-- View Modal  -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="user-details"></div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- For sucess  -->
 <!-- Success Modal -->
<div class="modal fade modal-ok" id="ok">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body d-flex justify-content-center align-items-center flex-column">
        <svg width="400" height="400">
          <circle fill="none" stroke="#68E534" stroke-width="20" cx="200" cy="200" r="190" class="circle" stroke-linecap="round" transform="rotate(-90 200 200) " />
          <polyline fill="none" stroke="#68E534" stroke-width="24" points="88,214 173,284 304,138" stroke-linecap="round" stroke-linejoin="round" class="tick" />
        </svg>
        <h2 class="pt-2">Sucsess</h2>
      </div>
    </div>
  </div>
</div>
<!-- For error -->
<!-- Error Modal -->
<div class="modal fade modal-fail" id="fail">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body d-flex justify-content-center align-items-center flex-column">
        <svg width="200" height="200">
          <circle fill="none" stroke="red" stroke-width="10" cx="100" cy="100" r="90" class="circle-fail" stroke-linecap="round" transform="rotate(-90 100 100)" />
          <line x1="60" y1="60" x2="140" y2="140" stroke="red" stroke-width="12" stroke-linecap="round" class="cross" />
          <line x1="140" y1="60" x2="60" y2="140" stroke="red" stroke-width="12" stroke-linecap="round" class="cross" />
        </svg>
        <h2 class="pt-2 text-danger">Faild</h2>
      </div>
    </div>
  </div>
</div>
