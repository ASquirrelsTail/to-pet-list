$(function () {
  if (window.permissions && window.permissions.create) {
    var createButtonDiv = $('<div class="col-12 text-center"></div>');
    var createButton = $('<button type="button" class="btn btn-primary">Add a task</button>');
    createButton.on('click', function () {
      openTaskModal();
    });
    $('#task-list').after(createButtonDiv.append(createButton));
  }

  $('.task').each(function () {
    mountTask($(this));
  });
  
});

function mountTask(taskElement) {
  taskElement.find('button').each(function () {
    $(this).remove();
  });
  if (!taskElement.hasClass('task-completed') && window.permissions.complete) {
    var completeButton = $('<button class="task-complete-button btn btn-sm btn-success">Complete</button>');
    completeButton.on('click', function() {
      completeButton.prop('disabled', true);
      window.axios.post(taskElement.data('task-path'))
                  .then(function (response) {
        console.log(response);
        taskElement.addClass('task-completed');
        completeButton.fadeOut(400, function () {
          completeButton.remove();
        });
        pushToast('Successfully completed task.');
      });
    });
    taskElement.append(completeButton);
  }
  if (window.permissions.update) {
    var editButton = $('<button class="btn btn-sm btn-primary">Edit</button>')
    editButton.on('click', function () {
      openTaskModal({id: taskElement.data('task-id'), name: taskElement.find('.task-name').text(), completed: taskElement.hasClass('task-completed')});
    });
    taskElement.append(editButton);
  }
  if (window.permissions.delete) {
    var deleteButton = $('<button class="btn btn-sm btn-danger">Delete</button>');
    deleteButton.on('click', function() {
      taskElement.find('button').each(function () {
        $(this).prop('disabled', true);
      });
      window.axios.post(taskElement.data('task-path'), {'_method': 'DELETE'})
                  .then(function (response) {
        taskElement.fadeOut(400, function () {
          $(this).remove();
          if ($('#task-list .task').length === 0) $('#task-list').append($('<p class="empty">You need to get some animals to pet!</p>'));
          pushToast('Successfully deleted task.')
        });
      });
    });
    taskElement.append(deleteButton);
  }
}

function openTaskModal(task) {
  if (task === undefined) {
    // New Task
    $('#taskModalLabel').text('New Task');
    $('#task_name').val('');
    $('#task_completed').prop('checked', false);
    $('#task_submit').text('Create New Task');
    $('#task_form').off('submit')
                   .on('submit', function(e) {
                      e.preventDefault();
                      $('#task_submit').prop('disabled', true);
                      if (validateTask()) addTask($('#task_name').val(), 
                                                  $('#task_priority').prop('checked'), 
                                                  $('#task_completed').prop('checked'));
                      else $('#task_submit').prop('disabled', false);
                   });

  } else {
    // Update Task
    $('#taskModalLabel').text('Edit Task');
    $('#task_name').val(task.name);
    $('#task_completed').prop('checked', task.completed);
    $('#task_submit').text('Update Task');
    $('#task_form').off('submit')
                   .on('submit', function(e) {
                      e.preventDefault();
                      $('#task_submit').prop('disabled', true);
                      if (validateTask()) updateTask(task.id, 
                                                     $('#task_name').val(), 
                                                     $('#task_priority').prop('checked'), 
                                                     $('#task_completed').prop('checked'));
                      else $('#task_submit').prop('disabled', false);
                   });
  }
  $('#task_priority').prop('checked', false);

  $('#task_name').removeClass('is-invalid')
                 .find('.invalid-feeback')
                 .each(function () {
                    $(this).remove();
                 });

  $('#taskModal').modal('show');
}

function validateTask() {
  if ($('#task_name').val().trim() === '') {
    $('#task_name').addClass('is-invalid')
                   .siblings('.invalid-feedback').remove();
    $('#task_name').after($('<span class="invalid-feedback" role="alert"><strong>The name field is required.</strong></span>'));
    return false;
  } else if ($('#task_name').val().length > 100) {
    $('#task_name').addClass('is-invalid')
                   .after($('<span class="invalid-feedback" role="alert"><strong>The name may not be greater than 100 characters.</strong></span>'));
    return false;
  }
  return true;
}

function addTask(name, priority, completed) {
  var data = {name: name};
  if (priority) data.priority = true;
  if (completed) data.completed = true;
  window.axios.post($('#task_form').attr('action'), data)
              .then(function (response) {
    var newTask = $('<li class="task"></li>');
    newTask.attr('id', 'task-' + response.data.id)
           .attr('data-task-id', response.data.id)
           .append($('<div class="task-name"></div>').text(name));
    if (completed) newTask.addClass('task-completed');

    if (priority) $('#task-list').prepend(newTask);
    else $('#task-list').append(newTask);

    mountTask(newTask);
    $('#task-list .empty').remove();

    $('#taskModal').modal('hide');
    $('#task_submit').prop('disabled', false);
    scrollToTask(response.data.id);
    pushToast('Successfully created task.');
  });
}

function updateTask(id, name, priority, completed) {
  var oldTask = $('*[data-task-id="' + id + '"]');

  var data = {'name': name, '_method': 'PATCH'};
  if (priority) data.priority = true;
  if (completed) data.completed = true;

  window.axios.post(oldTask.data('task-path'), data)
              .then(function () {
    oldTask.find('.task-name').text(name);
    if (completed) oldTask.addClass('task-completed');
    else oldTask.removeClass('task-completed');

    if (priority) {
      oldTask.detach();
      $('#task-list').prepend(oldTask);
    }

    mountTask(oldTask);
    $('#taskModal').modal('hide');
    $('#task_submit').prop('disabled', false);
    scrollToTask(id);
    pushToast('Successfully updated task.')
  });
}

function scrollToTask(id) {
  $('html, body').animate({
    scrollTop: $('#task-' + id).offset().top
  }, 800, function(){
    window.location.hash = 'task-' + id;
  });
}


var pushToast = require('./utilities.js').pushToast;

