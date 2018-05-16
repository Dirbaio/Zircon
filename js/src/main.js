import Vue from 'vue';
import api from './api';

Vue.config.productionTip = false;

for(const el of document.querySelectorAll('[data-component]')) {
    const compName = el.getAttribute('data-component');
    /* eslint-disable global-require, import/no-dynamic-require */
    const comp = require(`./components/${compName}`).default;

    let props = {};

    const propsAttr = el.getAttribute('data-props');
    if(propsAttr) {
        props = {
            ...props,
            ...JSON.parse(propsAttr),
        };
    }

    for(const k of Object.keys(el.dataset)) {
        if(k !== 'component' && k !== 'props') {
            props[k] = JSON.parse(el.dataset[k]);
        }
    }

    /* eslint-disable no-new, no-loop-func */
    new Vue({
        data: props,
        el,
        render: h => h(comp, { props }),
    });
}

function setCookie(sKey, sValue, vEnd, sPath, sDomain, bSecure) {
    if(!sKey || /^(?:expires|max-age|path|domain|secure)$/.test(sKey)) { return; }
    const sExpires = `; max-age=${vEnd}`;
    const lol = `${escape(sKey)}=${escape(sValue)}${sExpires}${sDomain ? `; domain=${sDomain}` : ''}${sPath ? `; path=${sPath}` : ''}${bSecure ? '; secure' : ''}`;
    document.cookie = lol;
}

window.toggleMobileLayout = (enabled) => {
    setCookie('mobileversion', enabled, 20 * 365 * 24 * 60 * 60, '/');
    window.location.reload();
};

window.postDelete = (pid) => {
    const reason = prompt('Enter a reason for deletion:');
    if(reason === null) return;

    api('/postdelete', {
        pid,
        del: 1,
        reason,
    });
};

window.threadRename = (tid) => {
    const name = prompt('Enter new thread name');
    if(name === null) return;
    api('/threadrename', {
        tid,
        name,
    });
};

window.logout = () => {
    api('/logout', {});
};
