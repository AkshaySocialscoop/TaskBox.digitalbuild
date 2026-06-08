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