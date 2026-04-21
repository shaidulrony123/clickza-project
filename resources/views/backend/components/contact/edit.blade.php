<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Contact Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">

                    <input type="hidden" id="updateContactId">

                    <div class="form-group-d">
                        <label>Name</label>
                        <input type="text" id="updateContactName" readonly>
                    </div>

                    <div class="form-group-d">
                        <label>Email</label>
                        <input type="email" id="updateContactEmail" readonly>
                    </div>

                    <div class="form-group-d">
                        <label>Subject</label>
                        <input type="text" id="updateContactSubject" readonly>
                    </div>

                    <div class="form-group-d">
                        <label>Message</label>
                        <textarea id="updateContactMessage" rows="4" readonly></textarea>
                    </div>

                    <div class="form-group-d">
                        <label>Status</label>
                        <select id="updateContactStatus">
                            <option value="new">New</option>
                            <option value="read">Read</option>
                            <option value="replied">Replied</option>
                        </select>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateContactBtn" onclick="contactUpdate()">
                        Update Status <i class="fas fa-save ms-2"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getCsrfToken() {
        let token = document.querySelector('meta[name="csrf-token"]');

        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'CSRF token not found',
                text: 'Blade head section e csrf meta tag add koro.'
            });
            return null;
        }

        return token.getAttribute('content');
    }

    async function FillUpUpdateForm(id) {
        try {
            let csrfToken = getCsrfToken();
            if (!csrfToken) return;

            document.getElementById('updateContactId').value = id;

            let res = await axios.post('/contact-by-id', {
                id: id
            }, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            let data = res.data.row;

            let subjectText = data.subject ?? '';
            if (data.subject === 'laravel') {
                subjectText = 'Laravel Project';
            } else if (data.subject === 'bug') {
                subjectText = 'Bug Fix';
            } else if (data.subject === 'wordpress') {
                subjectText = 'WordPress Help';
            }

            document.getElementById('updateContactName').value = data.name ?? '';
            document.getElementById('updateContactEmail').value = data.email ?? '';
            document.getElementById('updateContactSubject').value = subjectText;
            document.getElementById('updateContactMessage').value = data.message ?? '';
            document.getElementById('updateContactStatus').value = data.status ?? 'new';

        } catch (error) {
            console.error(error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load data',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }

    async function contactUpdate() {
        let updateBtn = document.getElementById('updateContactBtn');

        try {
            let csrfToken = getCsrfToken();
            if (!csrfToken) return;

            let id = document.getElementById('updateContactId').value;
            let status = document.getElementById('updateContactStatus').value;

            updateBtn.disabled = true;

            let res = await axios.post('/contact-update', {
                id: id,
                status: status
            }, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                let modalEl = document.getElementById('updateModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }

                await getContactData();

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated Successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

        } catch (error) {
            updateBtn.disabled = false;
            console.error(error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }
</script>