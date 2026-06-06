let currentTaskId = null;



// Open Task Detail Modal

document.addEventListener('DOMContentLoaded', () => {

    refreshTaskCounts();

    const modal = document.getElementById('taskDetailModal');

    const statusSelect = document.getElementById('modalTaskStatus');



    modal.addEventListener('show.bs.modal', function (event) {

        const btn = event.relatedTarget;



        currentTaskId = btn.dataset.taskId;



        document.getElementById('modalTaskTitle').textContent = btn.dataset.title;

        document.getElementById('modalTaskDescription').textContent = btn.dataset.description;

        document.getElementById('modalTaskAssigned').textContent = btn.dataset.assigned;

        document.getElementById('modalTaskDue').textContent = btn.dataset.due;

        document.getElementById('modalTaskPriority').textContent = btn.dataset.priority;

        document.getElementById('modalTaskId').value = btn.dataset.taskId;
        const commentJson = btn.getAttribute('data-comment');
        document.getElementById('modalTaskComment').value = commentJson ? JSON.parse(commentJson) : '';


        statusSelect.value = btn.dataset.status;



        updateModalProgress(btn.dataset.progress, btn.dataset.status);

    });



});



// Task Status Update

function updateTaskStatus(status) {



    fetch(window.taskConfig.updateUrl, {

        method: "POST",

        headers: {

            "Content-Type": "application/json",

            "X-CSRF-TOKEN": document

                .querySelector('meta[name="csrf-token"]')

                .getAttribute('content')

        },

        body: JSON.stringify({

            task_id: currentTaskId,

            status: status

        })

    })

    .then(res => res.json())

    .then(data => {



        if (!data.success) return;



       const btn = document.querySelector(

            `[data-task-id="${currentTaskId}"]`

        );



        btn.dataset.status = status;

        btn.dataset.progress = data.progress;



        // Update modal

        updateModalProgress(data.progress, status);



        // Update task list card

        updateTaskCard(currentTaskId, data.progress, data.statusLabel, data.statusClass);



        // 🔥 UPDATE COUNTERS

        refreshTaskCounts();

    })

    .catch(err => console.error(err));

}



// MODAL PROGRESS

function updateModalProgress(progress, status) {

    const bar = document.getElementById('modalTaskProgressBar');

    const text = document.getElementById('modalTaskProgressText');



    bar.style.width = progress + '%';

    bar.className = 'progress-bar rounded-pill bg-' + getStatusClass(status);

    text.textContent = progress + '%';

}



// TASK CARD UPDATE

function updateTaskCard(taskId, progress, statusLabel, statusClass) {



    const card = document.getElementById('task-card-' + taskId);

    if (!card) return;



    card.querySelector('.progress-bar').style.width = progress + '%';

    card.querySelector('.progress-bar').className = 'progress-bar bg-' + statusClass;



    card.querySelector('.badge-status').className =

        'badge-status bg-' + statusClass + '-subtle text-' + statusClass;



    card.querySelector('.badge-status').textContent = statusLabel;



    card.querySelector('small.text-muted').textContent = progress + '% Complete';

}



// STATUS → COLOR

function getStatusClass(status) {

    return {

        'Completed': 'success',

        'In_Progress': 'primary',

        'Pending': 'danger'

    }[status] ?? 'secondary';

}



function refreshTaskCounts() {

    fetch(window.taskConfig.countsUrl, {

        method: 'GET',

        headers: {

            'Accept': 'application/json',

            'X-Requested-With': 'XMLHttpRequest'

        }

    })

    .then(response => {

        if (!response.ok) {

            throw new Error('HTTP error ' + response.status);

        }

        return response.json();

    })

    .then(data => {

        document.getElementById('countCompleted').innerText = data.completed;

        document.getElementById('countTotal').innerText = data.total;

        document.getElementById('countInProgress').innerText = data.in_progress;

        document.getElementById('countOverdue').innerText = data.overdue;

    })

    .catch(error => {

        console.error('Counts error:', error);

    });

}



refreshTaskCounts();





// Edit User Modal Population

document.addEventListener('DOMContentLoaded', function () {



    const editModal = new bootstrap.Modal(

        document.getElementById('projectDetailsUpdateModal')

    );



    document.querySelectorAll('.editProjectDetailBtn').forEach(button => {

        button.addEventListener('click', function () {



            document.getElementById('edit_id').value           = this.dataset.id;

            document.getElementById('edit_name').value         = this.dataset.name;

            document.getElementById('edit_format').value       = this.dataset.format;

            document.getElementById('edit_link').value         = this.dataset.link;

            document.getElementById('edit_requirement').value  = this.dataset.requirement;

            document.getElementById('edit_comments').value     = this.dataset.comments;

            document.getElementById('edit_status').value       = this.dataset.status;

            const form = document.getElementById('editProjectDetailForm');

            form.action = `/projects/${this.dataset.id}`;



            editModal.show();

        });

    });



});







// Post status Update

function toggleStatus(id, currentStatus, badgeEl) {



    if (currentStatus === 'Completed') {

        Swal.fire({

            icon: 'info',

            title: 'Already Completed',

            text: 'This task is already marked as completed.',

            confirmButtonText: 'OK'

        });

        return;

    }



    Swal.fire({

        title: 'Mark as Completed?',

        text: 'Are you sure you want to mark this task as completed?',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Yes, complete it!',

        cancelButtonText: 'Cancel',

        reverseButtons: true

    }).then((result) => {



        // ❌ If cancelled → STOP HERE

        if (!result.isConfirmed) return;



        // ✅ Only runs when YES is clicked

        const formData = new FormData();

        formData.append('status', 'completed');



        fetch(`/calendar/${id}/status`, {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content

            },

            body: JSON.stringify({

                status: 'Completed'

            })

        })

        .then(res => {

            if (!res.ok) throw new Error('Request failed');

            return res.json();

        })

        .then(() => {



            // ✅ Update UI

            badgeEl.classList.remove('bg-danger');

            badgeEl.classList.add('bg-success');

            badgeEl.innerText = 'Status - completed';



            // ✅ Update JS memory

            Object.keys(meetings).forEach(date => {

                meetings[date].forEach(m => {

                    if (m.id === id) {

                        m.status = 'completed';

                    }

                });

            });



            Swal.fire({

                icon: 'success',

                title: 'Completed!',

                text: 'Task marked as completed successfully.',

                timer: 1500,

                showConfirmButton: false

            });



        })

        .catch(err => {

            console.error(err);

            Swal.fire({

                icon: 'error',

                title: 'Failed',

                text: 'Failed to update status'

            });

        });

    });

}





// Filter Tasks by Status

document.addEventListener('DOMContentLoaded', function () {



    const buttons = document.querySelectorAll('[data-filter]');

    const tasks = document.querySelectorAll('.task-card');



    buttons.forEach(button => {

        button.addEventListener('click', function () {



            // Active button UI

            buttons.forEach(btn => btn.classList.remove('active'));

            this.classList.add('active');



            const filter = this.dataset.filter;



            tasks.forEach(task => {

                const status = task.dataset.status;



                if (filter === 'all') {

                    task.style.display = '';

                }

                else if (filter === 'pending') {

                    // Pending + In Progress

                    task.style.display =

                        (status === 'Pending' || status === 'In_Progress')

                        ? ''

                        : 'none';

                }

                else if (filter === 'completed') {

                    task.style.display =

                        (status === 'Completed')

                        ? ''

                        : 'none';

                }

            });

        });

    });



});