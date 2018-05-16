window.getUrlForPath = function(path) {
    if(path == '/')
        return './';
    return './?' + path;
};
