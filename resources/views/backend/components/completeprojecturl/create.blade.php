<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Complete Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Project Title</label>
                            <input type="text" id="projectTitle" placeholder="Enter project title">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="projectStatus">
                                <option value="live">Live</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Project Image</label>
                        <input type="file" id="completeProjectImage" accept="image/*" onchange="previewCompleteProjectImg(event)">
                        <div style="margin-top:10px;">
                            <img id="newCompleteProjectImg"
                                 src="{{ asset('assets/images/default.jpg') }}"
                                 alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Url</label>
                            <input type="url" id="projectLink" placeholder="https://example.com">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="saveProjectBtn" onclick="projectCreate()">
                        Save Complete Project <i class="fas fa-save ms-2"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showLoader() {
        // optional
    }

    function hideLoader() {
        // optional
    }

    function previewCompleteProjectImg(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('newCompleteProjectImg');

        if (file) {
            preview.src = URL.createObjectURL(file);
        } else {
            preview.src = "{{ asset('assets/images/default.jpg') }}";
        }
    }

    async function projectCreate() {
        const saveBtn = document.getElementById('saveProjectBtn');

        try {
            let projectTitle = document.getElementById('projectTitle').value.trim();
            let projectStatus = document.getElementById('projectStatus').value;
            let projectLink = document.getElementById('projectLink').value.trim();
            let projectImage = document.getElementById('completeProjectImage').files[0];

            if (!projectTitle) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Project title is required',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            if (!projectLink) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Project URL is required',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('title', projectTitle);
            formData.append('status', projectStatus);
            formData.append('url', projectLink);

            if (projectImage) {
                formData.append('image', projectImage);
            }

            let res = await axios.post('/complete-project-create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('projectTitle').value = '';
                document.getElementById('projectStatus').value = 'live';
                document.getElementById('projectLink').value = '';
                document.getElementById('completeProjectImage').value = '';
                document.getElementById('newCompleteProjectImg').src = "{{ asset('assets/images/default.jpg') }}";

                let modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (modalInstance) {
                    modalInstance.hide();
                }

                if (typeof getCompleteProjectData === 'function') {
                    await getCompleteProjectData();
                }

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Project created successfully',
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

            console.error('Error creating complete project:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Something went wrong',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>