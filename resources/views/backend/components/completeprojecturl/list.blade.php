<div class="dash-section active" id="sec-projects">
    <div class="section-toolbar">
        <h2 class="section-h">Complete Projects URL</h2>
        <button class="btn-primary-d" id="addProjectBtn" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Add Complete Project
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="example">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Url</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        getCompleteProjectData();
    });

    async function getCompleteProjectData() {
        try {
            let res = await axios.get('/complete-project-list');
            let tableList = $("#tableList");

            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            tableList.empty();

            let rows = res.data.rows || [];

            rows.forEach(function (item) {
                let statusClass = '';
                let statusText = '';

                if (item.status == 1 || item.status === '1' || item.status === 'live' || item.status === 'Live') {
                    statusClass = 'live';
                    statusText = 'Live';
                } else {
                    statusClass = 'archive';
                    statusText = 'Archived';
                }

                let imagePath = item.image
                    ? `/frontend/images/projectcompleteurllist/${item.image}`
                    : '/frontend/images/no-image.png';

                let row = `
                    <tr>
                        <td>${item.id ?? ''}</td>
                        <td>${item.title ?? ''}</td>
                        <td>
                            <img src="${imagePath}" alt="Complete Project Image"
                                style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                        </td>
                        <td>
                            ${item.url ? `<a href="${item.url}" target="_blank">${item.url}</a>` : ''}
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
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

            $('#example').DataTable({
                order: [[0, 'desc']],
                lengthMenu: [10, 20, 50, 100],
                responsive: true
            });

        } catch (error) {
            console.error('Error fetching project data:', error);
        }
    }
</script>