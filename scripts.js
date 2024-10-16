const elements = {
    projectFormContainer: document.getElementById('projectFormContainer'),
    taskFormContainer: document.getElementById('taskFormContainer'),
    editProjectFormContainer: document.getElementById('editProjectFormContainer'),
    editTaskFormContainer: document.getElementById('editTaskFormContainer'),
    addProjectBtn: document.getElementById('addProjectBtn'),
    addTaskBtn: document.getElementById('addTaskBtn'),
    closeFormBtn: document.getElementById('closeForm'),
    closeTaskFormBtn: document.getElementById('closeTaskForm'),
    closeEditProjectFormBtn: document.getElementById('closeEditProjectForm'),
    closeEditTaskFormBtn: document.getElementById('closeEditTaskForm'),
    projectForm: document.getElementById('projectForm'),
    taskForm: document.getElementById('taskForm'),
    editProjectForm: document.getElementById('editProjectForm'),
    editTaskForm: document.getElementById('editTaskForm'),
  };
  
  const formHandling = {
    openForm: (container) => {
      container.classList.remove('translate-x-full');
    },
    closeForm: (container) => {
      container.classList.add('translate-x-full');
    },
  };

document.getElementById('editProjectForm').addEventListener('submit', function(event) {
  event.preventDefault();

  var projectId = document.getElementById('projectId').value;
  var projectName = document.getElementById('editProjectName').value;
  var projectStatus = document.getElementById('editProjectStatus').value;
  var completionProgress = document.getElementById('editCompletionProgress').value;

  var data = {
      projectId: projectId,
      projectName: projectName,
      projectStatus: projectStatus,
      completionProgress: completionProgress
  };

  // Kirim data ke server menggunakan AJAX
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'edit_project.php', true);
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.send(JSON.stringify(data));

  xhr.onload = function() {
      if (xhr.status === 200) {
          console.log(xhr.responseText);
      } else {
          console.log('Error: ' + xhr.status);
      }
  };
});
  
  document.addEventListener('DOMContentLoaded', () => {
    elements.addProjectBtn.addEventListener('click', () => {
      formHandling.openForm(elements.projectFormContainer);
    });
  
    elements.addTaskBtn.addEventListener('click', () => {
      formHandling.openForm(elements.taskFormContainer);
    });
  
    elements.closeFormBtn.addEventListener('click', () => {
      formHandling.closeForm(elements.projectFormContainer);
    });
  
    elements.closeTaskFormBtn.addEventListener('click', () => {
      formHandling.closeForm(elements.taskFormContainer);
    });
  
    elements.closeEditProjectFormBtn.addEventListener('click', () => {
      formHandling.closeForm(elements.editProjectFormContainer);
    });
  
    elements.closeEditTaskFormBtn.addEventListener('click', () => {
      formHandling.closeForm(elements.editTaskFormContainer);
    });
  
    elements.projectForm.addEventListener('submit', (event) => {
      event.preventDefault();
      saveProject();
    });
  
    elements.taskForm.addEventListener('submit', (event) => {
      event.preventDefault();
      saveTask();
    });
  
    elements.editProjectForm.addEventListener('submit', (event) => {
      event.preventDefault();
      updateProject();
    });
  
    elements.editTaskForm.addEventListener('submit', (event) => {
      event.preventDefault();
      updateTask();
    });
  });
  
  window.openProjectForm = (projectId) => {
    formHandling.openForm(elements.editProjectFormContainer);
  };
  
  window.openTaskForm = (projectId) => {
    document.getElementById('taskProject').value = projectId;
    formHandling.openForm(elements.taskFormContainer);
  };
  
  window.openTaskEditForm = (taskId, projectId) => {
    formHandling.openForm(elements.editTaskFormContainer);
  };

  function calculateProjectCompletionProgress(projectId) {
    const projectTasks = tasks.filter(task => task.projectId === projectId);
    const totalWeight = projectTasks.reduce((acc, task) => acc + task.weight, 0);
    const totalWeightCompleted = projectTasks.reduce((acc, task) => {
      if (task.status === 'completed') {
        return acc + task.weight;
      }
      return acc;
    }, 0);
  
    const completionProgress = (totalWeightCompleted / totalWeight) * 100;
    return completionProgress;
  }
  
  function saveProject() {
    const projectName = document.getElementById('projectName').value;
    const projectStatus = document.getElementById('projectStatus').value;
    const completionProgress = document.getElementById('completionProgress').value;
  
    fetch('save_project.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `name=${projectName}&status=${projectStatus}&completion_progress=${completionProgress}`,
    })
      .then((response) => response.text())
      .then((data) => {
        alert('Project saved successfully!');
        formHandling.closeForm(elements.projectFormContainer); 
        location.reload(); 
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Failed to save project. Please try again.');
      });
  }
  
  function saveTask() {
    const taskName = document.getElementById('taskName').value;
    const taskStatus = document.getElementById('taskStatus').value;
    const taskProject = document.getElementById('taskProject').value;
    const taskWeight = document.getElementById('taskWeight').value;
  
    fetch('save_task.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `name=${taskName}&status=${taskStatus}&project_id=${taskProject}&weight=${taskWeight}`,
    })
      .then((response) => response.text())
      .then((data) => {
        alert('Task saved successfully!');
        formHandling.closeForm(elements.taskFormContainer); 
        location.reload(); 
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Failed to save task. Please try again.');
      });
  }
  
  function updateProject() {
    const projectId = document.getElementById('editProjectName').dataset.projectId; // ID project
    const projectName = document.getElementById('editProjectName').value;
    const projectStatus = document.getElementById('editProjectStatus').value;
    const completionProgress = document.getElementById('editCompletionProgress').value;
  
    const data = {
      id: projectId,
      name: projectName,
      status: projectStatus,
      completion_progress: completionProgress,
    };
  
    fetch('update_project.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.text())
      .then((data) => {
        alert(data);
        location.reload(); // Reload page after update
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Failed to update project!');
      });
  }
  
  function updateTask() {
    const taskId = document.getElementById('editTaskName').dataset.taskId;
    const taskName = document.getElementById('editTaskName').value;
    const taskStatus = document.getElementById('editTaskStatus').value;
    const taskWeight = document.getElementById('editTaskWeight').value;
    const tasks = [
      { id: 1, projectId: 1, weight: 2, status: 'in_progress' },
      { id: 2, projectId: 1, weight: 1, status: 'in_progress' },
      // ...
    ];

    const data = {
      id: taskId,
      name: taskName,
      status: taskStatus,
      weight: taskWeight,
    };
  
    fetch('update_task.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.text())
      .then((data) => {
        console.log(`Response: ${data}`); // Log the response
        alert(data);
  
        const projectId = document.getElementById('taskProject').value;
        const completionProgress = calculateProjectCompletionProgress(projectId);
        updateProjectCompletionProgress(projectId, completionProgress);
  
        location.reload(); 
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Failed to update task!');
      });
  }
  
  function updateProjectCompletionProgress(projectId, completionProgress) {
    fetch('update_project.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: projectId, completion_progress: completionProgress }),
    })
      .then((response) => response.text())
      .then((data) => {
        console.log(`Response: ${data}`); 
        alert(data);
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Failed to update project completion progress!');
      });
  }
  
  function deleteProject(projectId) {
    console.log('Delete project button clicked');
    if (confirm('Are you sure you want to delete this project?')) {
      console.log('Confirm dialog confirmed');
      fetch('delete_project.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${projectId}`,
      })
        .then((response) => response.text())
        .then((data) => {
          console.log(`Response from server: ${data}`);
          alert(data);
          location.reload(); 
        })
        .catch((error) => {
          console.error('Error:', error);
          alert('Failed to delete project!');
        });
    }
  }
  
  function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
      fetch('delete_task.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${taskId}`,
      })
        .then((response) => response.text())
        .then((data) => {
          alert(data);
          location.reload(); 
        })
        .catch((error) => {
          console.error('Error:', error);
          alert('Failed to delete task!');
        });
    }
  }