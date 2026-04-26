@extends('backend.layouts.app')

<style>
    #dashboardTodoList .todo-table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
        color: #e5e7eb;
    }

    #dashboardTodoList .todo-table thead th {
        background: rgba(255, 255, 255, 0.06);
        color: #ffffff;
        padding: 14px 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        font-weight: 700;
        text-align: left;
    }

    #dashboardTodoList .todo-table tbody td {
        background: rgba(255, 255, 255, 0.03);
        color: #d1d5db;
        padding: 14px 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        vertical-align: middle;
    }

    #dashboardTodoList .todo-task-title {
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 4px;
    }

    #dashboardTodoList .todo-task-desc {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }

    #dashboardTodoList .todo-completed {
        text-decoration: line-through;
        opacity: 0.6;
    }

    #dashboardTodoList .todo-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    #dashboardTodoList .todo-btn {
        border: none;
        border-radius: 8px;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
    }

    #dashboardTodoList .todo-btn-success {
        background: #198754;
    }

    #dashboardTodoList .todo-btn-info {
        background: #0dcaf0;
        color: #000;
    }

    #dashboardTodoList .todo-btn-danger {
        background: #dc3545;
    }

    #dashboardTodoList .todo-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    #dashboardTodoList .todo-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    #dashboardTodoList .todo-badge-pending {
        background: #fbbc04;
        color: #111827;
    }

    #dashboardTodoList .todo-badge-completed {
        background: #198754;
        color: #ffffff;
    }

    #dashboardHandnoteList .handnote-list {
        display: grid;
        gap: 14px;
    }

    #dashboardHandnoteList .handnote-item {
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 16px;
    }

    #dashboardHandnoteList .handnote-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    #dashboardHandnoteList .handnote-title {
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    #dashboardHandnoteList .handnote-target {
        color: #9ca3af;
        font-size: 13px;
        margin: 0;
    }

    #dashboardHandnoteList .handnote-note {
        color: #d1d5db;
        font-size: 14px;
        margin: 0 0 12px;
        white-space: pre-line;
    }

    #dashboardHandnoteList .handnote-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    #dashboardHandnoteList .handnote-date {
        color: #9ca3af;
        font-size: 13px;
    }

    #dashboardHandnoteList .handnote-actions {
        display: flex;
        gap: 8px;
    }

    #dashboardHandnoteList .handnote-btn {
        border: none;
        border-radius: 10px;
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
    }

    #dashboardHandnoteList .handnote-btn-edit {
        background: #0d6efd;
    }

    #dashboardHandnoteList .handnote-btn-delete {
        background: #dc3545;
    }

    #dashboardHandnoteList .handnote-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    #dashboardHandnoteList .handnote-badge-active {
        background: #198754;
        color: #ffffff;
    }

    #dashboardHandnoteList .handnote-badge-inactive {
        background: #6b7280;
        color: #ffffff;
    }
</style>

@section('content')
@php
    $hour = now()->hour;

    if ($hour < 12) {
        $greeting = 'Good morning';
    } elseif ($hour < 17) {
        $greeting = 'Good afternoon';
    } elseif ($hour < 20) {
        $greeting = 'Good evening';
    } else {
        $greeting = 'Good night';
    }
@endphp

<div class="dash-section active" id="sec-overview">

    <div class="welcome-bar">
        <div>
            <h2 class="welcome-text">
                {{ $greeting }},
                <span class="accent-text">{{ auth()->user()->name ?? 'Developer' }}</span> 👋
            </h2>
            <p>Here's what's happening with your dashboard.</p>
        </div>

        <a href="{{ url('/') }}" class="btn-primary-d">
            <i class="fas fa-eye me-2"></i>Live Preview
        </a>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon si-blue">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-info">
                <span class="stat-val" data-target="{{ (int) ($totalProjects ?? 0) }}">0</span>
                <p>Total Projects</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-purple">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <span class="stat-val" data-target="{{ (int) ($totalContacts ?? 0) }}">0</span>
                <p>Total Contacts</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-green">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <span class="stat-val" data-target="{{ (int) ($totalProducts ?? 0) }}">0</span>
                <p>Total Products</p>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="dash-card mt-4">
                <div class="card-header-d d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">To Do List</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openTodoCreateBox()">
                        + Add
                    </button>
                </div>

                <div class="p-3">
                    <div id="todoCreateBox" style="display:none; margin-bottom:15px;">
                        <div class="form-group-d mb-2">
                            <input type="text" id="dashboardTodoTask" class="form-control" placeholder="Enter task">
                        </div>

                        <div class="form-group-d mb-2">
                            <textarea id="dashboardTodoDescription" class="form-control" rows="3" placeholder="Enter description"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" onclick="dashboardTodoCreate()">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="closeTodoCreateBox()">Cancel</button>
                        </div>
                    </div>

                    <div id="dashboardTodoList"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dash-card mt-4">
                <div class="card-header-d d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Hand Notes</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openHandnoteCreateBox()">
                        + Add
                    </button>
                </div>

                <div class="p-3">
                    <div id="handnoteCreateBox" style="display:none; margin-bottom:15px;">
                        <div class="form-group-d mb-2">
                            <input type="text" id="dashboardHandnoteTitle" class="form-control" placeholder="Enter title">
                        </div>

                        <div class="form-group-d mb-2">
                            <input type="text" id="dashboardHandnoteTarget" class="form-control" placeholder="Enter target">
                        </div>

                        <div class="form-group-d mb-2">
                            <textarea id="dashboardHandnoteNote" class="form-control" rows="3" placeholder="Enter note"></textarea>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <input type="date" id="dashboardHandnoteTargetDate" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <select id="dashboardHandnoteStatus" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" onclick="dashboardHandnoteCreate()">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="closeHandnoteCreateBox()">Cancel</button>
                        </div>
                    </div>

                    <div id="dashboardHandnoteList"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-row finance-stats-row mt-4">
        <div class="stat-card">
            <div class="stat-icon si-orange">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-info">
                <span class="stat-money">TK {{ number_format((float) ($paidInvoiceRevenue ?? 0), 2) }}</span>
                <p>Paid Revenue</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-purple">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-money">TK {{ number_format((float) ($paidInvoiceCost ?? 0), 2) }}</span>
                <p>Internal Cost</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-green">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <span class="stat-money">TK {{ number_format((float) ($paidInvoiceProfit ?? 0), 2) }}</span>
                <p>Net Profit</p>
            </div>
            <span class="stat-trend neutral">{{ number_format((float) ($paidInvoiceMargin ?? 0), 1) }}%</span>
        </div>
    </div>

</div>

{{-- Edit Modal --}}
<div class="modal fade" id="todoEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none; border-radius:16px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Edit To Do</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="editTodoId">

                    <div class="form-group-d mb-3">
                        <label>Task</label>
                        <input type="text" id="editTodoTask" class="form-control" placeholder="Enter task">
                    </div>

                    <div class="form-group-d mb-3">
                        <label>Description</label>
                        <textarea id="editTodoDescription" class="form-control" rows="4" placeholder="Enter description"></textarea>
                    </div>

                    <div class="form-group-d mb-3">
                        <label>Status</label>
                        <select id="editTodoStatus" class="form-control">
                            <option value="0">Pending</option>
                            <option value="1">Completed</option>
                        </select>
                    </div>

                    <button type="button" class="btn-primary-d" onclick="dashboardTodoUpdate()">
                        Update To Do <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="todoDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3 text-warning">Delete!</h3>
                <p class="mb-3">Once deleted, you can't get it back.</p>
                <input type="hidden" id="deleteTodoId">
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" id="todo-delete-close" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="dashboardTodoDelete()">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="handnoteEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none; border-radius:16px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hand Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="editHandnoteId">

                    <div class="form-group-d mb-3">
                        <label>Title</label>
                        <input type="text" id="editHandnoteTitle" class="form-control" placeholder="Enter title">
                    </div>

                    <div class="form-group-d mb-3">
                        <label>Target</label>
                        <input type="text" id="editHandnoteTarget" class="form-control" placeholder="Enter target">
                    </div>

                    <div class="form-group-d mb-3">
                        <label>Note</label>
                        <textarea id="editHandnoteNote" class="form-control" rows="4" placeholder="Enter note"></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label>Target Date</label>
                            <input type="date" id="editHandnoteTargetDate" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select id="editHandnoteStatus" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" onclick="dashboardHandnoteUpdate()">
                        Update Hand Note <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="handnoteDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3 text-warning">Delete!</h3>
                <p class="mb-3">Once deleted, you can't get it back.</p>
                <input type="hidden" id="deleteHandnoteId">
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="dashboardHandnoteDelete()">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.stat-val');

    counters.forEach(counter => {
        const target = parseInt(counter.dataset.target) || 0;
        let current = 0;

        if (target === 0) {
            counter.textContent = '0';
            return;
        }

        const increment = Math.max(1, Math.ceil(target / 50));

        const updateCounter = () => {
            current += increment;

            if (current >= target) {
                counter.textContent = target.toLocaleString();
            } else {
                counter.textContent = current.toLocaleString();
                requestAnimationFrame(updateCounter);
            }
        };

        updateCounter();
    });

    loadDashboardTodos();
    loadDashboardHandnotes();
});

function openTodoCreateBox() {
    document.getElementById('todoCreateBox').style.display = 'block';
}

function closeTodoCreateBox() {
    document.getElementById('todoCreateBox').style.display = 'none';
    document.getElementById('dashboardTodoTask').value = '';
    document.getElementById('dashboardTodoDescription').value = '';
}

function openHandnoteCreateBox() {
    document.getElementById('handnoteCreateBox').style.display = 'block';
}

function closeHandnoteCreateBox() {
    document.getElementById('handnoteCreateBox').style.display = 'none';
    document.getElementById('dashboardHandnoteTitle').value = '';
    document.getElementById('dashboardHandnoteTarget').value = '';
    document.getElementById('dashboardHandnoteNote').value = '';
    document.getElementById('dashboardHandnoteTargetDate').value = '';
    document.getElementById('dashboardHandnoteStatus').value = '1';
}

async function loadDashboardTodos() {
    try {
        let res = await axios.get('/todolist-list');
        let list = document.getElementById('dashboardTodoList');
        let rows = res.data.rows || [];

        list.innerHTML = '';

        if (rows.length === 0) {
            list.innerHTML = `<p class="text-muted mb-0">No to do found.</p>`;
            return;
        }

        let table = `
            <table class="todo-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">ID</th>
                        <th>Task</th>
                        <th style="width: 220px;">Status</th>
                        <th style="width: 220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        rows.forEach(item => {
            const isCompleted = Number(item.is_completed) === 1;

            table += `
                <tr>
                    <td>${item.id ?? ''}</td>
                    <td>
                        <div class="todo-task-title ${isCompleted ? 'todo-completed' : ''}">
                            ${item.task ?? ''}
                        </div>
                        <p class="todo-task-desc ${isCompleted ? 'todo-completed' : ''}">
                            ${item.description ?? ''}
                        </p>
                    </td>
                    <td>
                        <span class="todo-badge ${isCompleted ? 'todo-badge-completed' : 'todo-badge-pending'}">
                            ${isCompleted ? 'Completed' : 'Pending'}
                        </span>
                    </td>
                    <td>
                        <div class="todo-actions">
                            <button
                                class="todo-btn todo-btn-success"
                                onclick="toggleDashboardTodoStatus(${item.id}, true)"
                                ${isCompleted ? 'disabled' : ''}
                                title="Complete"
                            >
                                <i class="fas fa-check"></i>
                            </button>

                            <button
                                class="todo-btn todo-btn-info"
                                onclick="openDashboardTodoEditModal(${item.id})"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            <button
                                class="todo-btn todo-btn-danger"
                                onclick="openDashboardTodoDeleteModal(${item.id})"
                                title="Delete"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        table += `
                </tbody>
            </table>
        `;

        list.innerHTML = table;

    } catch (error) {
        console.error('Todo load error:', error);
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Failed to load todo list',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function loadDashboardHandnotes() {
    try {
        let res = await axios.get('/handnote-list');
        let list = document.getElementById('dashboardHandnoteList');
        let rows = res.data.rows || [];

        list.innerHTML = '';

        if (rows.length === 0) {
            list.innerHTML = `<p class="text-muted mb-0">No hand note found.</p>`;
            return;
        }

        let html = `<div class="handnote-list">`;

        rows.forEach(item => {
            const isActive = Number(item.status) === 1;
            const targetText = item.target ? item.target : 'No target';
            const noteText = item.note ? item.note : 'No note added yet.';
            const dateText = item.target_date ? item.target_date : 'No target date';

            html += `
                <div class="handnote-item">
                    <div class="handnote-top">
                        <div>
                            <div class="handnote-title">${item.title ?? ''}</div>
                            <p class="handnote-target">Target: ${targetText}</p>
                        </div>
                        <span class="handnote-badge ${isActive ? 'handnote-badge-active' : 'handnote-badge-inactive'}">
                            ${isActive ? 'Active' : 'Inactive'}
                        </span>
                    </div>

                    <p class="handnote-note">${noteText}</p>

                    <div class="handnote-meta">
                        <span class="handnote-date">Target Date: ${dateText}</span>

                        <div class="handnote-actions">
                            <button class="handnote-btn handnote-btn-edit" onclick="openDashboardHandnoteEditModal(${item.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="handnote-btn handnote-btn-delete" onclick="openDashboardHandnoteDeleteModal(${item.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        list.innerHTML = html;

    } catch (error) {
        console.error('Hand note load error:', error);
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Failed to load hand notes',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function dashboardTodoCreate() {
    try {
        let task = document.getElementById('dashboardTodoTask').value.trim();
        let description = document.getElementById('dashboardTodoDescription').value.trim();

        if (!task || !description) {
            Swal.fire({
                icon: 'warning',
                title: 'Task and description are required',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        let res = await axios.post('/todolist-create', {
            task: task,
            description: description,
            is_completed: 0
        });

        if (res.data.status === 'success') {
            closeTodoCreateBox();
            await loadDashboardTodos();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'To do created successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Create failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function dashboardHandnoteCreate() {
    try {
        let title = document.getElementById('dashboardHandnoteTitle').value.trim();
        let target = document.getElementById('dashboardHandnoteTarget').value.trim();
        let note = document.getElementById('dashboardHandnoteNote').value.trim();
        let target_date = document.getElementById('dashboardHandnoteTargetDate').value;
        let status = document.getElementById('dashboardHandnoteStatus').value;

        if (!title) {
            Swal.fire({
                icon: 'warning',
                title: 'Title is required',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        let res = await axios.post('/handnote-create', {
            title: title,
            target: target,
            note: note,
            target_date: target_date,
            status: status
        });

        if (res.data.status === 'success') {
            closeHandnoteCreateBox();
            await loadDashboardHandnotes();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'Hand note created successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Create failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function openDashboardTodoEditModal(id) {
    try {
        let res = await axios.post('/todolist-by-id', { id: id });
        let data = res.data.row ?? {};

        document.getElementById('editTodoId').value = data.id ?? '';
        document.getElementById('editTodoTask').value = data.task ?? '';
        document.getElementById('editTodoDescription').value = data.description ?? '';
        document.getElementById('editTodoStatus').value = Number(data.is_completed) === 1 ? '1' : '0';

        const modalEl = document.getElementById('todoEditModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

    } catch (error) {
        console.error('Edit load error:', error);
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Failed to load todo',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function dashboardTodoUpdate() {
    try {
        let id = document.getElementById('editTodoId').value;
        let task = document.getElementById('editTodoTask').value.trim();
        let description = document.getElementById('editTodoDescription').value.trim();
        let is_completed = document.getElementById('editTodoStatus').value;

        if (!task || !description) {
            Swal.fire({
                icon: 'warning',
                title: 'Task and description are required',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        let res = await axios.post('/todolist-update', {
            id: id,
            task: task,
            description: description,
            is_completed: is_completed
        });

        if (res.data.status === 'success') {
            const modalEl = document.getElementById('todoEditModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();

            await loadDashboardTodos();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'To do updated successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Update failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function openDashboardHandnoteEditModal(id) {
    try {
        let res = await axios.post('/handnote-by-id', { id: id });
        let data = res.data.row ?? {};

        document.getElementById('editHandnoteId').value = data.id ?? '';
        document.getElementById('editHandnoteTitle').value = data.title ?? '';
        document.getElementById('editHandnoteTarget').value = data.target ?? '';
        document.getElementById('editHandnoteNote').value = data.note ?? '';
        document.getElementById('editHandnoteTargetDate').value = data.target_date ?? '';
        document.getElementById('editHandnoteStatus').value = Number(data.status) === 1 ? '1' : '0';

        const modalEl = document.getElementById('handnoteEditModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

    } catch (error) {
        console.error('Hand note edit load error:', error);
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Failed to load hand note',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function dashboardHandnoteUpdate() {
    try {
        let id = document.getElementById('editHandnoteId').value;
        let title = document.getElementById('editHandnoteTitle').value.trim();
        let target = document.getElementById('editHandnoteTarget').value.trim();
        let note = document.getElementById('editHandnoteNote').value.trim();
        let target_date = document.getElementById('editHandnoteTargetDate').value;
        let status = document.getElementById('editHandnoteStatus').value;

        if (!title) {
            Swal.fire({
                icon: 'warning',
                title: 'Title is required',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        let res = await axios.post('/handnote-update', {
            id: id,
            title: title,
            target: target,
            note: note,
            target_date: target_date,
            status: status
        });

        if (res.data.status === 'success') {
            const modalEl = document.getElementById('handnoteEditModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();

            await loadDashboardHandnotes();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'Hand note updated successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Update failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

function openDashboardTodoDeleteModal(id) {
    document.getElementById('deleteTodoId').value = id;

    const modalEl = document.getElementById('todoDeleteModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function openDashboardHandnoteDeleteModal(id) {
    document.getElementById('deleteHandnoteId').value = id;

    const modalEl = document.getElementById('handnoteDeleteModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

async function dashboardTodoDelete() {
    try {
        let id = document.getElementById('deleteTodoId').value;

        let res = await axios.post('/todolist-delete', { id: id });

        const modalEl = document.getElementById('todoDeleteModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();

        if (res.data.status === 'success') {
            await loadDashboardTodos();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'To do deleted successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Delete failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function dashboardHandnoteDelete() {
    try {
        let id = document.getElementById('deleteHandnoteId').value;

        let res = await axios.post('/handnote-delete', { id: id });

        const modalEl = document.getElementById('handnoteDeleteModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();

        if (res.data.status === 'success') {
            await loadDashboardHandnotes();

            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'Hand note deleted successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Delete failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

async function toggleDashboardTodoStatus(id, checked) {
    try {
        let resById = await axios.post('/todolist-by-id', { id: id });
        let todo = resById.data.row;

        await axios.post('/todolist-update', {
            id: todo.id,
            task: todo.task,
            description: todo.description,
            is_completed: checked ? 1 : 0
        });

        await loadDashboardTodos();

        Swal.fire({
            icon: 'success',
            title: 'Task completed successfully',
            showConfirmButton: false,
            timer: 1500
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Status update failed',
            showConfirmButton: false,
            timer: 2000
        });
    }
}
</script>
@endsection
