document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const taskId = button.dataset.taskId;
    const username = button.dataset.username;
    const email = button.dataset.email;
    const text = button.dataset.text;
    const status = button.dataset.status;

    document.getElementById('editId').value = taskId;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editText').value = text;
    document.getElementById('editStatus').checked = status === '1';
});

document.getElementById('editModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('editForm').reset();
});
