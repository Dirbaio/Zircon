function getCsrfToken() {
    for(const el of document.getElementsByTagName('meta')) {
        if(el.getAttribute('name') === 'csrftoken') {
            return el.getAttribute('content');
        }
    }
    return '';
}
function api(path, param) {
    return new Promise((resolve, reject) => {
        const fullParams = {
            csrftoken: getCsrfToken(),
            ...param,
        };

        const xhr = new XMLHttpRequest();
        xhr.open('POST', `/api${path}`);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

        xhr.onload = () => {
            if(xhr.status >= 200 && xhr.status < 300) {
                const res = JSON.parse(xhr.response);
                if(res.redirect) {
                    window.location = res.redirect;
                }
                resolve(res);
            } else {
                if(xhr.status === 400) {
                    reject(JSON.parse(xhr.response).message);
                }
                reject('Error connecting to server');
            }
        };
        xhr.onerror = () => {
            reject('Error connecting to server');
        };
        xhr.send(JSON.stringify(fullParams));
    });
}


export default function(path, param) {
    const res = api(path, param);
    res.catch((error) => {
        /* eslint-disable no-alert */
        alert(error);
    });
    return res;
}
