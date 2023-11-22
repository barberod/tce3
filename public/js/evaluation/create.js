const hasLabField = document.getElementById('evaluation_create_evaluationBasics_hasLab');
const labFieldSet = document.getElementById('labFieldSet');

hasLabField.addEventListener('change', function() {
  if (this.value === 'Yes') {
    labFieldSet.style.display = 'block';
  } else {
    labFieldSet.style.display = 'none';
  }
});

if (hasLabField.value !== 'Yes') {
  labFieldSet.style.display = 'none';
}

const usaField = document.getElementById('evaluation_create_evaluationInstitution_locatedUsa');
const usaLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_locatedUsa"]');
const stateField = document.getElementById('evaluation_create_evaluationInstitution_state');
const stateLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_state"]');
const institutionField = document.getElementById('evaluation_create_evaluationInstitution_institution');
const institutionLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_institution"]');
const listedField = document.getElementById('evaluation_create_evaluationInstitution_institutionListed');
const listedLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_institutionListed"]');
const countryField = document.getElementById('evaluation_create_evaluationInstitution_country');
const countryLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_country"]');
const institutionNameField = document.getElementById('evaluation_create_evaluationInstitution_institutionName');
const institutionNameLabel = document.querySelector('label[for="evaluation_create_evaluationInstitution_institutionName"]');
const institutionNameHelp = document.getElementById('evaluation_create_evaluationInstitution_institutionName_help');

usaField.addEventListener('change', function() {
  determineInstitutionSituation()
});

listedField.addEventListener('change', function() {
  determineInstitutionSituation()
});

stateField.addEventListener('change', function() {
  determineInstitutionSituation()
});

function showOrHideInstitutionSelector() {
  if (usaField.value === 'Yes' && listedField.value === 'Yes' && stateField.value !== '') {
    institutionField.style.display = 'block';
    institutionLabel.style.display = 'block';
  } else {
    institutionField.style.display = 'none';
    institutionLabel.style.display = 'none';
  }
}

function showOrHideInstitutionListedSelector() {
  if (usaField.value === 'Yes' && stateField.value !== '') {
    listedField.style.display = 'block';
    listedLabel.style.display = 'block';
  } else {
    listedField.style.display = 'none';
    listedLabel.style.display = 'none';
  }
}

function showOrHideInstitutionNameField() {
  if (
    usaField.value === 'No' ||
    (usaField.value === 'Yes' && listedField.value === 'No' && stateField.value !== '')
  ) {
    institutionNameField.style.display = 'block';
    institutionNameLabel.style.display = 'block';
    institutionNameHelp.style.display = 'block';
  } else {
    institutionNameField.style.display = 'none';
    institutionNameLabel.style.display = 'none';
    institutionNameHelp.style.display = 'none';
  }
}

function determineInstitutionSituation() {
  if (usaField.value === 'Yes' && listedField.value === 'Yes') {
    whenUsaInstitutionIsListed();
  } else if (usaField.value === 'Yes' && listedField.value === 'No') {
    whenUsaInstitutionIsNotListed();
  } else if (usaField.value === 'No') {
    whenInternationalInstitution();
  }
  showOrHideInstitutionSelector();
  showOrHideInstitutionListedSelector();
  showOrHideInstitutionNameField();
}

function whenUsaInstitutionIsListed() {
  usaField.style.display = 'block';
  usaLabel.style.display = 'block';
  stateField.style.display = 'block';
  stateLabel.style.display = 'block';
  institutionField.style.display = 'block';
  institutionLabel.style.display = 'block';
  listedField.style.display = 'block';
  listedLabel.style.display = 'block';
  countryField.style.display = 'none';
  countryLabel.style.display = 'none';
  institutionNameField.style.display = 'none';
  institutionNameLabel.style.display = 'none';
  institutionNameHelp.style.display = 'none';
}

function whenUsaInstitutionIsNotListed() {
  usaField.style.display = 'block';
  usaLabel.style.display = 'block';
  stateField.style.display = 'block';
  stateLabel.style.display = 'block';
  institutionField.style.display = 'none';
  institutionLabel.style.display = 'none';
  listedField.style.display = 'block';
  listedLabel.style.display = 'block';
  countryField.style.display = 'none';
  countryLabel.style.display = 'none';
  institutionNameField.style.display = 'block';
  institutionNameLabel.style.display = 'block';
  institutionNameHelp.style.display = 'block';
}

function whenInternationalInstitution() {
  usaField.style.display = 'block';
  usaLabel.style.display = 'block';
  stateField.style.display = 'none';
  stateLabel.style.display = 'none';
  institutionField.style.display = 'none';
  institutionLabel.style.display = 'none';
  listedField.style.display = 'none';
  listedLabel.style.display = 'none';
  countryField.style.display = 'block';
  countryLabel.style.display = 'block';
  institutionNameField.style.display = 'block';
  institutionNameLabel.style.display = 'block';
  institutionNameHelp.style.display = 'block';
}

determineInstitutionSituation();

document.addEventListener('DOMContentLoaded', function () {
  const stateSelect = document.getElementById('evaluation_create_evaluationInstitution_state');
  const institutionSelect = document.getElementById('evaluation_create_evaluationInstitution_institution');

  stateSelect.addEventListener('change', function () {
    const selectedState = stateSelect.value;

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          updateInstitutionOptions(response);
        } else {
          // Handle error if needed
        }
      }
    };

    xhr.open('GET', '/get-institutions?usState=' + selectedState);
    xhr.send();
  });

  function updateInstitutionOptions(data) {
    institutionSelect.innerHTML = '<option value="">Select an institution</option>';
    Object.keys(data).forEach(function (key) {
      const option = document.createElement('option');
      option.value = data[key];
      option.textContent = key;
      institutionSelect.appendChild(option);
    });
  }
});
