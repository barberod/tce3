const addNoteField = document.getElementById('evaluation_resubmit_addNote');
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
