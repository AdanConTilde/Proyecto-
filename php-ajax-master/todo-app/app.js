document.addEventListener('DOMContentLoaded', function() {
  let edit = false;

  console.log('JavaScript is working!');
  fetchTasks();
  document.getElementById('task-result').style.display = 'none';

  // Search key type event
  document.getElementById('search').addEventListener('keyup', function() {
    const search = this.value;
    if (search) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'task-search.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          let template = '';
          response.forEach(task => {
            template += `<li><a href="#" class="task-item">${task.name}</a></li>`;
          });
          document.getElementById('task-result').style.display = 'block';
          document.getElementById('container').innerHTML = template;
        }
      };
      xhr.send(`search=${search}`);
    }
  });

  // Form submission
  document.getElementById('task-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const postData = new URLSearchParams({
      name: document.getElementById('name').value,
      description: document.getElementById('description').value,
      id: document.getElementById('taskId').value
    });

    const url = edit === false ? 'task-add.php' : 'task-edit.php';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        console.log(xhr.responseText);
        document.getElementById('task-form').reset();
        fetchTasks();
      }
    };
    xhr.send(postData.toString());
  });

  // Fetching Tasks
  function fetchTasks() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'tasks-list.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        const tasks = JSON.parse(xhr.responseText);
        let template = '';
        tasks.forEach(task => {
          template += `
            <tr taskId="${task.id}">
              <td>${task.id}</td>
              <td><a href="#" class="task-item">${task.name}</a></td>
              <td>${task.description}</td>
              <td><button class="task-delete btn btn-danger">Delete</button></td>
            </tr>`;
        });
        document.getElementById('tasks').innerHTML = template;
      }
    };
    xhr.send();
  }

  // Get a Single Task by Id
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('task-item')) {
      e.preventDefault();
      const element = e.target.closest('tr');
      const id = element.getAttribute('taskId');
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'task-single.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          const task = JSON.parse(xhr.responseText);
          document.getElementById('name').value = task.name;
          document.getElementById('description').value = task.description;
          document.getElementById('taskId').value = task.id;
          edit = true;
        }
      };
      xhr.send(`id=${id}`);
    }
  });

  // Delete a Single Task
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('task-delete')) {
      if (confirm('Are you sure you want to delete it?')) {
        const element = e.target.closest('tr');
        const id = element.getAttribute('taskId');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'task-delete.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          if (xhr.status === 200) {
            console.log(xhr.responseText);
            fetchTasks();
          }
        };
        xhr.send(`id=${id}`);
      }
    }
  });
});
