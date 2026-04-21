<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Project</h5>
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
                                <option value="dev">Dev</option>
                                <option value="live">Live</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Description</label>
                        <textarea id="updateProjectDescription" rows="4" placeholder="Enter project description"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Category</label>
                            <select id="updateProjectCategory" class="form-control">
                                <option value="">Select Category</option>
                            </select>
                        </div>

                        <div class="form-group-d">
                            <label>Views</label>
                            <input type="number" id="updateProjectViews" value="0" min="0">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Project Image</label>
                        <input type="file" id="updateProjectImage" accept="image/*" onchange="previewUpdateProjectImg(event)">
                        <div style="margin-top:10px;">
                            <img id="updateProjectPreview" src="{{ asset('assets/images/default.jpg') }}" alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Teach Stack</label>
                        <div id="updateTeachStackWrapper"></div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addUpdateTeachStackField()">Add Item</button>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Project Link</label>
                            <input type="url" id="updateProjectLink" placeholder="https://example.com">
                        </div>
                        <div class="form-group-d">
                            <label>GitHub Link</label>
                            <input type="url" id="updateGithubLink" placeholder="https://github.com/username/repo">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateProjectBtn" onclick="projectUpdate()">
                        Update Project <i class="fas fa-save ms-2"></i>
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
        preview.src = file ? URL.createObjectURL(file) : "{{ asset('assets/images/default.jpg') }}";
    }

    function addUpdateTeachStackField(value = '') {
        let wrapper = document.getElementById('updateTeachStackWrapper');

        let div = document.createElement('div');
        div.className = 'teach-stack-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="updateProjectTeachStack form-control" placeholder="Enter teach stack" value="${value}">
            <button type="button" class="btn btn-danger" onclick="removeUpdateTeachStack(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeUpdateTeachStack(button) {
        let wrapper = document.getElementById('updateTeachStackWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function loadUpdateCategoryOptions(selectedId = '') {
        try {
            let res = await axios.get('/category-list');
            let categorySelect = document.getElementById('updateProjectCategory');

            categorySelect.innerHTML = `<option value="">Select Category</option>`;

            res.data.rows.forEach(function (item) {
                let selected = String(selectedId) === String(item.id) ? 'selected' : '';
                categorySelect.innerHTML += `<option value="${item.id}" ${selected}>${item.name}</option>`;
            });
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    async function FillUpUpdateForm(id) {
        try {
            document.getElementById('updateProjectId').value = id;
            document.getElementById('updateTeachStackWrapper').innerHTML = '';

            let res = await axios.post('/project-by-id', { id: id });
            let data = res.data.row ?? res.data.data ?? res.data;

            document.getElementById('updateProjectTitle').value = data.title ?? '';
            document.getElementById('updateProjectDescription').value = data.description ?? '';
            document.getElementById('updateProjectStatus').value = data.status ?? 'dev';
            document.getElementById('updateProjectLink').value = data.project_link ?? '';
            document.getElementById('updateGithubLink').value = data.github_link ?? '';
            document.getElementById('updateProjectViews').value = data.views ?? 0;
            document.getElementById('oldProjectImage').value = data.image ?? '';
            document.getElementById('updateProjectPreview').src = data.image ? `/${data.image}` : "{{ asset('assets/images/default.jpg') }}";

            await loadUpdateCategoryOptions(data.category_id ?? '');

            let teach_stacks = [];
            try {
                teach_stacks = data.teach_stack
                    ? (Array.isArray(data.teach_stack) ? data.teach_stack : JSON.parse(data.teach_stack))
                    : [];
            } catch (e) {
                teach_stacks = [];
            }

            if (teach_stacks.length > 0) {
                teach_stacks.forEach(teac => addUpdateTeachStackField(teac));
            } else {
                addUpdateTeachStackField('');
            }

        } catch (error) {
            console.error('Error loading project data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Failed to load project data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function projectUpdate() {
        const updateBtn = document.getElementById('updateProjectBtn');

        try {
            const projectId = document.getElementById('updateProjectId').value;
            const projectTitle = document.getElementById('updateProjectTitle').value.trim();
            const projectDescription = document.getElementById('updateProjectDescription').value.trim();
            const projectCategory = document.getElementById('updateProjectCategory').value;
            const projectImage = document.getElementById('updateProjectImage').files[0];
            const oldProjectImage = document.getElementById('oldProjectImage').value;
            const projectStatus = document.getElementById('updateProjectStatus').value;
            const projectLink = document.getElementById('updateProjectLink').value.trim();
            const githubLink = document.getElementById('updateGithubLink').value.trim();
            const projectViews = document.getElementById('updateProjectViews').value;

            let teachStackInputs = document.querySelectorAll('.updateProjectTeachStack');
            let teach_stacks = [];

            teachStackInputs.forEach((item) => {
                if (item.value.trim() !== '') {
                    teach_stacks.push(item.value.trim());
                }
            });

            if (!projectTitle || !projectDescription || !projectCategory || teach_stacks.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Title, Description, Category and at least one teach stack are required!',
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
            formData.append('description', projectDescription);
            formData.append('category_id', projectCategory);
            formData.append('status', projectStatus);
            formData.append('project_link', projectLink);
            formData.append('github_link', githubLink);
            formData.append('views', projectViews ? projectViews : 0);
            formData.append('old_image', oldProjectImage);

            teach_stacks.forEach((teac, index) => {
                formData.append(`teach_stacks[${index}]`, teac);
            });

            if (projectImage) {
                formData.append('image', projectImage);
            }

            let res = await axios.post('/project-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('updateProjectImage').value = '';

                const modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getProjectData();

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