function fetchTasks() {
    fetch('tasks-list.php')
    .then(response => response.json())
    .then(data => {
        let template = '';
        data.forEach(task => {
            template += `
                <tr taskId="${task.id}">
                    <td>${task.id}</td>
                    <td>
                        <a href="#" class="task-item">${task.name}</a>
                    </td>
                    <td>${task.description}</td>
                    <td>
                        <button class="task-delete btn btn-danger">Delete</button>
                    </td>
                </tr>`;
        });
        document.getElementById('tasks').innerHTML = template;
    })
    .catch(error => console.error('Error fetching tasks:', error));
}
