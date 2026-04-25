<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Hand Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Title</label>
                            <input type="text" id="handnoteTitle" placeholder="Enter note title">
                        </div>

                        <div class="form-group-d">
                            <label>Target</label>
                            <input type="text" id="handnoteTarget" placeholder="Enter target">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Note</label>
                        <textarea id="handnoteNote" rows="4" placeholder="Enter note details"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Target Date</label>
                            <input type="date" id="handnoteTargetDate">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="handnoteStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="saveHandnoteBtn" onclick="handnoteCreate()">
                        Save Note <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showLoader() {
        // optional loader show
    }

    function hideLoader() {
        // optional loader hide
    }

    async function handnoteCreate() {
        const saveBtn = document.getElementById('saveHandnoteBtn');

        try {
            const title = document.getElementById('handnoteTitle').value.trim();
            const target = document.getElementById('handnoteTarget').value.trim();
            const note = document.getElementById('handnoteNote').value.trim();
            const targetDate = document.getElementById('handnoteTargetDate').value;
            const status = document.getElementById('handnoteStatus').value;

            if (!title) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Title is required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            let payload = {
                title: title,
                target: target,
                note: note,
                target_date: targetDate,
                status: status
            };

            let res = await axios.post('/handnote-create', payload);

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('handnoteTitle').value = '';
                document.getElementById('handnoteTarget').value = '';
                document.getElementById('handnoteNote').value = '';
                document.getElementById('handnoteTargetDate').value = '';
                document.getElementById('handnoteStatus').value = '1';

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getHandNoteData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Hand note created successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: res.data.message ?? 'Create failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            saveBtn.disabled = false;
            console.error('Error creating hand note:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
