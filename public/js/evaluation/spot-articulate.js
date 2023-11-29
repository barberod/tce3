const addNoteField = document.getElementById('evaluation_spot_articulate_addNote');
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

const eqvCnt = document.getElementById('evaluation_spot_articulate_eqvCnt');
const eqv1Grp = document.getElementById('eqv1Grp');
const eqv1OprGrp = document.getElementById('eqv1OprGrp');
const eqv2Grp = document.getElementById('eqv2Grp');
const eqv2OprGrp = document.getElementById('eqv2OprGrp');
const eqv3Grp = document.getElementById('eqv3Grp');
const eqv3OprGrp = document.getElementById('eqv3OprGrp');
const eqv4Grp = document.getElementById('eqv4Grp');

function showOrHideEqvGroups() {
  if (eqvCnt.value === '0'  || eqvCnt.value === '') {
    eqv1Grp.style.display = 'none';
    eqv1OprGrp.style.display = 'none';
    eqv2Grp.style.display = 'none';
    eqv2OprGrp.style.display = 'none';
    eqv3Grp.style.display = 'none';
    eqv3OprGrp.style.display = 'none';
    eqv4Grp.style.display = 'none';
  } else if (eqvCnt.value === '1') {
    eqv1Grp.style.display = 'block';
    eqv1OprGrp.style.display = 'none';
    eqv2Grp.style.display = 'none';
    eqv2OprGrp.style.display = 'none';
    eqv3Grp.style.display = 'none';
    eqv3OprGrp.style.display = 'none';
    eqv4Grp.style.display = 'none';
  } else if (eqvCnt.value === '2') {
    eqv1Grp.style.display = 'block';
    eqv1OprGrp.style.display = 'block';
    eqv2Grp.style.display = 'block';
    eqv2OprGrp.style.display = 'none';
    eqv3Grp.style.display = 'none';
    eqv3OprGrp.style.display = 'none';
    eqv4Grp.style.display = 'none';
  } else if (eqvCnt.value === '3') {
    eqv1Grp.style.display = 'block';
    eqv1OprGrp.style.display = 'block';
    eqv2Grp.style.display = 'block';
    eqv2OprGrp.style.display = 'block';
    eqv3Grp.style.display = 'block';
    eqv3OprGrp.style.display = 'none';
    eqv4Grp.style.display = 'none';
  } else if (eqvCnt.value === '4') {
    eqv1Grp.style.display = 'block';
    eqv1OprGrp.style.display = 'block';
    eqv2Grp.style.display = 'block';
    eqv2OprGrp.style.display = 'block';
    eqv3Grp.style.display = 'block';
    eqv3OprGrp.style.display = 'block';
    eqv4Grp.style.display = 'block';
  }
}

showOrHideEqvGroups();

eqvCnt.addEventListener('change', function() {
  showOrHideEqvGroups();
});

function updateEqvOptions(data, selector) {
  selector.innerHTML = '<option value="">- Select one -</option>';
  Object.keys(data).forEach(function (key) {
    const option = document.createElement('option');
    option.value = data[key];
    option.textContent = key;
    selector.appendChild(option);
  });
}

const eqv1SubjCode = document.getElementById('evaluation_spot_articulate_eqv1SubjCode');
const eqv1 = document.getElementById('evaluation_spot_articulate_eqv1');

eqv1SubjCode.addEventListener('change', function() {
  const selectedSubjCode = this.value;
  const xhr= new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        updateEqvOptions(response, eqv1);
      } else {
        // Handle error if needed
      }
    }
  };
  xhr.open('GET', '/get-courses?subjectCode=' + selectedSubjCode);
  xhr.send();
});

const eqv2SubjCode = document.getElementById('evaluation_spot_articulate_eqv2SubjCode');
const eqv2 = document.getElementById('evaluation_spot_articulate_eqv2');

eqv2SubjCode.addEventListener('change', function() {
  const selectedSubjCode = this.value;
  const xhr= new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        updateEqvOptions(response, eqv2);
      } else {
        // Handle error if needed
      }
    }
  };
  xhr.open('GET', '/get-courses?subjectCode=' + selectedSubjCode);
  xhr.send();
});

const eqv3SubjCode = document.getElementById('evaluation_spot_articulate_eqv3SubjCode');
const eqv3 = document.getElementById('evaluation_spot_articulate_eqv3');

eqv3SubjCode.addEventListener('change', function() {
  const selectedSubjCode = this.value;
  const xhr= new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        updateEqvOptions(response, eqv3);
      } else {
        // Handle error if needed
      }
    }
  };
  xhr.open('GET', '/get-courses?subjectCode=' + selectedSubjCode);
  xhr.send();
});

const eqv4SubjCode = document.getElementById('evaluation_spot_articulate_eqv4SubjCode');
const eqv4 = document.getElementById('evaluation_spot_articulate_eqv4');

eqv4SubjCode.addEventListener('change', function() {
  const selectedSubjCode = this.value;
  const xhr= new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        updateEqvOptions(response, eqv4);
      } else {
        // Handle error if needed
      }
    }
  };
  xhr.open('GET', '/get-courses?subjectCode=' + selectedSubjCode);
  xhr.send();
});