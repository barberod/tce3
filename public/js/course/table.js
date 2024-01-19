function isValidSubjectCode(subjectCode) {
    // Check if the subjectCode is between 2 and 8 characters long and contains only letters
    return /^[a-zA-Z]{2,8}$/.test(subjectCode);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('subjCodeBtn').addEventListener('click', function () {

        const subjectCode = document.getElementById('subjCodeSelector').value;
        let url = window.location.href;

        // Check if there are already parameters in the URL
        const separator = url.indexOf('?') !== -1 ? '&' : '?';

        if (isValidSubjectCode(subjectCode)) {
            // Remove the existing page parameter
            const existingPageParam = new URL(url).searchParams.get('page');
            if (existingPageParam) {
                url = url.replace(`page=${existingPageParam}`, ``);
                if (url.endsWith(separator)) {
                    url = url.slice(0, -1);
                }
            }
            
            // Replace the existing subj parameter or add it if it doesn't exist
            const existingSubjectCodeParam = new URL(url).searchParams.get('subj');
            if (existingSubjectCodeParam) {
                url = url.replace(`subj=${existingSubjectCodeParam}`, `subj=${subjectCode}`);
            } else {
                url += separator + 'subj=' + subjectCode;
            }
        } else {
            // Remove the subj parameter if it exists
            const newUrl = new URL(url);
            newUrl.searchParams.delete('subj');
            url = newUrl.toString();

            // Remove the separator if it is the last parameter
            if (url.endsWith(separator)) {
                url = url.slice(0, -1);
            }
        }

        window.location.href = url;
    });
});
