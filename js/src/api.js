function getCsrfToken() {
    for(const el of document.getElementsByTagName('meta')) {
        if(el.getAttribute('name') === 'csrftoken') {
            return el.getAttribute('content');
        }
    }
    return '';
}
function api(path, param, progress) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', `/api${path}`);

        let data;
        if(param instanceof File) {
            data = new FormData();
            data.append('file', param);
            data.append('csrftoken', getCsrfToken());
        } else {
            data = JSON.stringify({
                csrftoken: getCsrfToken(),
                ...param,
            });
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        }

        if(progress) {
            xhr.upload.onprogress = (e) => {
                if(e.lengthComputable) {
                    progress(e.loaded / e.total);
                } else {
                    progress(Math.random()); // lol
                }
            };
        }
        xhr.onload = () => {
            if(xhr.status >= 200 && xhr.status < 300) {
                let res;
                try {
                    res = JSON.parse(xhr.response);
                } catch (e) {
                    // Oh well, but whatever...
                    reject('Error connecting to server (invalid JSON repsonse)');
                }
                if(res.redirect) {
                    window.location = res.redirect;
                }
                resolve(res);
            } else if(xhr.status === 400) {
                let res;
                try {
                    res = JSON.parse(xhr.response);
                } catch (e) {
                    // Oh well, but whatever...
                    reject('Error connecting to server (400, invalid JSON repsonse)');
                }
                if(res.message) reject(res.message);
                reject(`Error connecting to server (${xhr.status})`);
            } else if(xhr.status === 413) {
                reject('File too large');
            } else if(xhr.status === 418) {
                reject('Server is a teapot?');
            } else {
                reject(`Error connecting to server (${xhr.status})`);
            }
        };
        xhr.onerror = () => {
            reject('Error connecting to server');
        };
        xhr.send(data);
    });
}

export default function(path, param, progress) {
    const res = api(path, param, progress);
    res.catch((error) => {
        /* eslint-disable no-alert */
        alert(error);
    });
    return res;
}
