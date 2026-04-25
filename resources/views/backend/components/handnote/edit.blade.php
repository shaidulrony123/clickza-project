<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Hand Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="updateHandnoteId">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Title</label>
                            <input type="text" id="updateHandnoteTitle" placeholder="Enter note title">
                        </div>

                        <div class="form-group-d">
                            <label>Target</label>
                            <input type="text" id="updateHandnoteTarget" placeholder="Enter target">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Note</label>
                        <textarea id="updateHandnoteNote" rows="4" placeholder="Enter note details"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Target Date</label>
                            <input type="date" id="updateHandnoteTargetDate">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="updateHandnoteStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateHandnoteBtn" onclick="handnoteUpdate()">
                        Update Note <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function FillUpUpdateForm(id) {
        try {
            let res = await axios.post('/handnote-by-id', { id: id });
            let data = res.data.row ?? {};

            document.getElementById('updateHandnoteId').value = data.id ?? '';
            document.getElementById('updateHandnoteTitle').value = data.title ?? '';
            document.getElementById('updateHandnoteTarget').value = data.target ?? '';
            document.getElementById('updateHandnoteNote').value = data.note ?? '';
            document.getElementById('updateHandnoteTargetDate').value = data.target_date ?? '';
            document.getElementById('updateHandnoteStatus').value = Number(data.status) === 1 ? '1' : '0';
        } catch (error) {
            console.error('Error loading hand note data:', error);
            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load hand note data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function handnoteUpdate() {
        const updateBtn = document.getElementById('updateHandnoteBtn');

        try {
            const id = document.getElementById('updateHandnoteId').value;
            const title = document.getElementById('updateHandnoteTitle').value.trim();
            const target = document.getElementById('updateHandnoteTarget').value.trim();
            const note = document.getElementById('updateHandnoteNote').value.trim();
            const targetDate = document.getElementById('updateHandnoteTargetDate').value;
            const status = document.getElementById('updateHandnoteStatus').value;

            if (!title) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Title is required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            updateBtn.disabled = true;
            showLoader();

            let res = await axios.post('/handnote-update', {
                id: id,
                title: title,
                target: target,
                note: note,
                target_date: targetDate,
                status: status
            });

            hideLoader();
            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                const modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getHandNoteData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Hand note updated successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: res.data.message ?? 'Update failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            updateBtn.disabled = false;
            console.error('Error updating hand note:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
