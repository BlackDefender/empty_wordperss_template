window.hotReload = (function () {

    function reloadCSS(file) {
        file = file.split('/wp-content/themes/');
        if (file.length === 1) {
            return;
        }
        _reloadCSS(file[1])
    }

    function _reloadCSS(file) {
        const link = document.body.parentNode.querySelectorAll('link[href*="/wp-content/themes/' + file + '"]');
        if (link.length === 0) {
            return;
        }
        const href = link.getAttribute('href').split('?')[0];
        link.setAttribute('href', href+'?'+Date.now());
    }

    function reloadPage() {
        window.location.reload();
    }

    function makeRequest(apiURL, requestParams) {
        fetch(apiURL, {
            method: 'POST',
            cache: "no-store",
            body: requestParams
        })
            .then((response) => {
                return response.text();
            })
            .then((data) => {
                switch (data.split('.').pop()) {
                    case 'css':
                        reloadCSS(data);
                        break;

                    case 'js':
                    case 'php':
                        reloadPage();
                        break;
                }
                makeRequest(apiURL, requestParams);
            });
    }

    return function (apiURL, watcherPatch) {
        const requestParams = new FormData();
        requestParams.append('themepath', watcherPatch);
        requestParams.append('filetypes', JSON.stringify(["css", "js", "php"]));
        makeRequest(apiURL, requestParams);
    };

})();