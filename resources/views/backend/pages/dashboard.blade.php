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
        <div class="col-lg-8">
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
});

function openTodoCreateBox() {
    document.getElementById('todoCreateBox').style.display = 'block';
}

function closeTodoCreateBox() {
    document.getElementById('todoCreateBox').style.display = 'none';
    document.getElementById('dashboardTodoTask').value = '';
    document.getElementById('dashboardTodoDescription').value = '';
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

function openDashboardTodoDeleteModal(id) {
    document.getElementById('deleteTodoId').value = id;

    const modalEl = document.getElementById('todoDeleteModal');
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