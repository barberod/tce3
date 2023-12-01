document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('reqAdmBtn').addEventListener('click', function () {

    const reqAdmValue = document.getElementById('reqAdmSelector').value;
    let url = window.location.href;

    // Check if there are already parameters in the URL
    const separator = url.indexOf('?') !== -1 ? '&' : '?';

    if (reqAdmValue === 'yes' || reqAdmValue === 'no') {
      // Replace the existing reqadm parameter or add it if it doesn't exist
      const existingReqAdmParam = new URL(url).searchParams.get('reqadm');
      if (existingReqAdmParam) {
        url = url.replace(`reqadm=${existingReqAdmParam}`, `reqadm=${reqAdmValue}`);
      } else {
        url += separator + 'reqadm=' + reqAdmValue;
      }
    } else {
      // Remove the reqadm parameter if it exists
      const newUrl = new URL(url);
      newUrl.searchParams.delete('reqadm');
      url = newUrl.toString();

      // Remove the separator if it is the last parameter
      if (url.endsWith(separator)) {
        url = url.slice(0, -1);
      }
    }

    window.location.href = url;
  });
});
