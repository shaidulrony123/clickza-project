<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Project</h5>
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
                                <option value="dev">Dev</option>
                                <option value="live">Live</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Description</label>
                        <textarea id="projectDescription" rows="4" placeholder="Enter project description"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Category</label>
                            <select id="projectCategory" class="form-control">
                                <option value="">Select Category</option>
                            </select>
                        </div>

                        <div class="form-group-d">
                            <label>Views</label>
                            <input type="number" id="projectViews" value="0" min="0">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Project Image</label>
                        <input type="file" id="projectImage" accept="image/*" onchange="previewProjectImg(event)">
                        <div style="margin-top:10px;">
                            <img id="newProjectImg" src="{{ asset('assets/images/default.jpg') }}" alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Teach Stacks</label>
                        <div id="teachStackWrapper">
                            <div class="teachstack-item" style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" class="projectTeachStack form-control" placeholder="Enter teach stack">
                                <button type="button" class="btn btn-danger" onclick="removeTeachStack(this)">X</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addTeachStackField()">Add Item</button>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Project Link</label>
                            <input type="url" id="projectLink" placeholder="https://example.com">
                        </div>
                        <div class="form-group-d">
                            <label>GitHub Link</label>
                            <input type="url" id="githubLink" placeholder="https://github.com/username/repo">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="saveProjectBtn" onclick="projectCreate()">
                        Save Project <i class="fas fa-save ms-2"></i>
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

    function previewProjectImg(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('newProjectImg');
        preview.src = file ? URL.createObjectURL(file) : "{{ asset('assets/images/default.jpg') }}";
    }

    function addTeachStackField() {
        let wrapper = document.getElementById('teachStackWrapper');

        let div = document.createElement('div');
        div.className = 'teachstack-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="projectTeachStack form-control" placeholder="Enter teach stack">
            <button type="button" class="btn btn-danger" onclick="removeTeachStack(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeTeachStack(button) {
        let wrapper = document.getElementById('teachStackWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function loadCategoryOptions() {
        try {
            let res = await axios.get('/category-list');
            let categorySelect = document.getElementById('projectCategory');

            categorySelect.innerHTML = `<option value="">Select Category</option>`;

            res.data.rows.forEach(function (item) {
                categorySelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    document.getElementById('exampleModal').addEventListener('show.bs.modal', function () {
        loadCategoryOptions();
    });

    async function projectCreate() {
        const saveBtn = document.getElementById('saveProjectBtn');

        try {
            const projectTitle = document.getElementById('projectTitle').value.trim();
            const projectDescription = document.getElementById('projectDescription').value.trim();
            const projectCategory = document.getElementById('projectCategory').value;
            const projectImage = document.getElementById('projectImage').files[0];
            const projectStatus = document.getElementById('projectStatus').value;
            const projectLink = document.getElementById('projectLink').value.trim();
            const githubLink = document.getElementById('githubLink').value.trim();
            const projectViews = document.getElementById('projectViews').value;

            let teachStackInputs = document.querySelectorAll('.projectTeachStack');
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

            saveBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('title', projectTitle);
            formData.append('description', projectDescription);
            formData.append('category_id', projectCategory);
            formData.append('status', projectStatus);
            formData.append('project_link', projectLink);
            formData.append('github_link', githubLink);
            formData.append('views', projectViews ? projectViews : 0);

            teach_stacks.forEach((teac, index) => {
                formData.append(`teach_stacks[${index}]`, teac);
            });

            if (projectImage) {
                formData.append('image', projectImage);
            }

            let res = await axios.post('/project-create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('projectTitle').value = '';
                document.getElementById('projectDescription').value = '';
                document.getElementById('projectCategory').value = '';
                document.getElementById('projectImage').value = '';
                document.getElementById('projectStatus').value = 'dev';
                document.getElementById('projectLink').value = '';
                document.getElementById('githubLink').value = '';
                document.getElementById('projectViews').value = 0;
                document.getElementById('newProjectImg').src = "{{ asset('assets/images/default.jpg') }}";

                document.getElementById('teachStackWrapper').innerHTML = `
                    <div class="teachstack-item" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" class="projectTeachStack form-control" placeholder="Enter teach stack">
                        <button type="button" class="btn btn-danger" onclick="removeTeachStack(this)">X</button>
                    </div>
                `;

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getProjectData();

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
            console.error('Error creating project:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>