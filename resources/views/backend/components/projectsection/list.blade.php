<div class="dash-section active" id="sec-projects">
    <div class="section-toolbar">
        <h2 class="section-h">Projects</h2>
        <button class="btn-primary-d" id="addProjectBtn" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Add Project
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="example">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Teach Stack</th>
                        <th>Project Link</th>
                        <th>GitHub Link</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getProjectData();

    async function getProjectData() {
        try {
            let res = await axios.get('/project-list');
            let tableList = $("#tableList");

            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            tableList.empty();

            res.data.rows.forEach(function (item) {
                let statusClass = '';
                let statusText = '';

                if (item.status === 'live') {
                    statusClass = 'live';
                    statusText = 'Live';
                } else if (item.status === 'dev') {
                    statusClass = 'dev';
                    statusText = 'Dev';
                } else {
                    statusClass = 'archive';
                    statusText = 'Archived';
                }

                let teachStacks = [];

                try {
                    teachStacks = item.teach_stack
                        ? (Array.isArray(item.teach_stack) ? item.teach_stack : JSON.parse(item.teach_stack))
                        : [];
                } catch (e) {
                    teachStacks = [];
                }

                let row = `
                    <tr>
                        <td>
                            <img src="/${item.image ?? ''}" alt="Project Image" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                        </td>
                        <td>${item.title ?? ''}</td>
                        <td>${item.description ?? ''}</td>
                        <td>
                            ${item.category ? `<span class="cat-badge">${item.category.name}</span>` : ''}
                        </td>
                        <td>
                            ${teachStacks.map(stack => `<span class="tech-tag">${stack}</span>`).join(' ')}
                        </td>
                        <td>
                            ${item.project_link ? `<a href="javascript:void(0)" onclick='increaseProjectView(${item.id}, ${JSON.stringify(item.project_link)})'>Visit</a>` : ''}
                        </td>
                        <td>
                            ${item.github_link ? `<a href="${item.github_link}" target="_blank">${item.github_link}</a>` : ''}
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>${item.views ?? 0}</td>
                        <td>
                            <div class="row-actions">
                                <button data-id="${item.id}" class="ra-btn edit editBtn">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button data-id="${item.id}" class="ra-btn del deleteBtn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                tableList.append(row);
            });

            $(document).off('click', '.editBtn').on('click', '.editBtn', async function () {
                let id = $(this).data('id');
                await FillUpUpdateForm(id);

                let updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
                updateModal.show();
            });

            $(document).off('click', '.deleteBtn').on('click', '.deleteBtn', function () {
                let id = $(this).data('id');
                $("#deleteID").val(id);

                let deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
                deleteModal.show();
            });

            new DataTable('#example', {
                order: [[8, 'desc']],
                lengthMenu: [10, 20, 50, 100],
            });

        } catch (error) {
            console.error('Error fetching project data:', error);
        }
    }

    async function increaseProjectView(id, url) {
        try {
            await axios.post('/project-view-count/' + id);
            window.open(url, '_blank');
            await getProjectData();
        } catch (error) {
            console.error('View count update failed:', error);
            window.open(url, '_blank');
        }
    }
</script>