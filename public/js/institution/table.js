function isValidStateCode(stateCode) {
    // Check if the stateCode is exactly two characters long and contains only letters
    return /^[a-zA-Z]{2}$/.test(stateCode);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('usStateBtn').addEventListener('click', function () {

        const stateCode = document.getElementById('usStateSelector').value;
        let url = window.location.href;

        // Check if there are already parameters in the URL
        const separator = url.indexOf('?') !== -1 ? '&' : '?';

        if (isValidStateCode(stateCode)) {
            // Remove the existing page parameter
            const existingPageParam = new URL(url).searchParams.get('page');
            if (existingPageParam) {
                url = url.replace(`page=${existingPageParam}`, ``);
                if (url.endsWith(separator)) {
                    url = url.slice(0, -1);
                }
            }
            
            // Replace the existing usstate parameter or add it if it doesn't exist
            const existingUsStateParam = new URL(url).searchParams.get('usstate');
            if (existingUsStateParam) {
                url = url.replace(`usstate=${existingUsStateParam}`, `usstate=${stateCode}`);
            } else {
                url += separator + 'usstate=' + stateCode;
            }
        } else {
            // Remove the usstate parameter if it exists
            const newUrl = new URL(url);
            newUrl.searchParams.delete('usstate');
            url = newUrl.toString();

            // Remove the separator if it is the last parameter
            if (url.endsWith(separator)) {
                url = url.slice(0, -1);
            }
        }

        window.location.href = url;
    });
});
