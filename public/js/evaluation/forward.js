const addNoteField = document.getElementById('evaluation_forward_addNote');
const noteFieldSet = document.getElementById('noteFieldSet');

addNoteField.addEventListener('change', function() {
  if (this.value === 'Yes') {
    noteFieldSet.style.display = 'block';
  } else {
    noteFieldSet.style.display = 'none';
  }
});

if (addNoteField.value !== 'Yes') {
  noteFieldSet.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
  const deptSelect = document.getElementById('evaluation_forward_dept');
  const assigneeSelect = document.getElementById('evaluation_forward_assignee');

  deptSelect.addEventListener('change', function () {
    const selectedDept = deptSelect.value;

    const xhr= new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          updateAssigneeOptions(response);
        } else {
          // Handle error if needed
        }
      }
    };

    xhr.open('GET', '/get-assignees?dept=' + selectedDept);
    xhr.send();
  });

  function updateAssigneeOptions(data) {
    assigneeSelect.innerHTML = '<option value="">- Select one -</option>';
    Object.keys(data).forEach(function (key) {
      const option = document.createElement('option');
      option.value = data[key];
      option.textContent = key;
      assigneeSelect.appendChild(option);
    });
  }
});