const addNoteField = document.getElementById('evaluation_from_r2_to_student_addNote');
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
