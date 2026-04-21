<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Complete Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">

                    <input type="hidden" id="updateProjectId">
                    <input type="hidden" id="oldProjectImage">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Project Title</label>
                            <input type="text" id="updateProjectTitle" placeholder="Enter project title">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="updateProjectStatus">
                                <option value="live">Live</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Url</label>
                            <input type="url" id="updateProjectLink" placeholder="https://example.com">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Project Image</label>
                        <input type="file" id="updateProjectImage" accept="image/*" onchange="previewUpdateProjectImg(event)">
                        <div style="margin-top:10px;">
                            <img id="updateProjectPreview"
                                 src="{{ asset('assets/images/default.jpg') }}"
                                 alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateProjectBtn" onclick="projectUpdate()">
                        Update Complete Project <i class="fas fa-save ms-2"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewUpdateProjectImg(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('updateProjectPreview');

        if (file) {
            preview.src = URL.createObjectURL(file);
        } else {
            preview.src = "{{ asset('assets/images/default.jpg') }}";
        }
    }

    async function FillUpUpdateForm(id) {
        try {
            document.getElementById('updateProjectId').value = id;

            let res = await axios.post('/complete-project-by-id', { id: id });
            let data = res.data.row ?? res.data.data ?? res.data;

            document.getElementById('updateProjectTitle').value = data.title ?? '';
            document.getElementById('updateProjectLink').value = data.url ?? '';
            document.getElementById('oldProjectImage').value = data.image ?? '';

            if (data.status == 1 || data.status === '1' || data.status === 'live' || data.status === 'Live') {
                document.getElementById('updateProjectStatus').value = 'live';
            } else {
                document.getElementById('updateProjectStatus').value = 'archived';
            }

            document.getElementById('updateProjectPreview').src = data.image
                ? `/frontend/images/projectcompleteurllist/${data.image}`
                : "{{ asset('assets/images/default.jpg') }}";

        } catch (error) {
            console.error('Error loading project data:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load project data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function projectUpdate() {
        const updateBtn = document.getElementById('updateProjectBtn');

        try {
            let projectId = document.getElementById('updateProjectId').value;
            let projectTitle = document.getElementById('updateProjectTitle').value.trim();
            let projectStatus = document.getElementById('updateProjectStatus').value;
            let projectLink = document.getElementById('updateProjectLink').value.trim();
            let projectImage = document.getElementById('updateProjectImage').files[0];
            let oldProjectImage = document.getElementById('oldProjectImage').value;

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

            updateBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('id', projectId);
            formData.append('title', projectTitle);
            formData.append('url', projectLink);
            formData.append('status', projectStatus);
            formData.append('old_image', oldProjectImage);

            if (projectImage) {
                formData.append('image', projectImage);
            }

            let res = await axios.post('/complete-project-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('updateProjectImage').value = '';

                let modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (modalInstance) {
                    modalInstance.hide();
                }

                if (typeof getCompleteProjectData === 'function') {
                    await getCompleteProjectData();
                }

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Project updated successfully',
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
            console.error('Error updating project:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>