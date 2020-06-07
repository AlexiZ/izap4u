const checkVisible = elm => {
    let rect = elm.getBoundingClientRect(),
        viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight)
    ;

    return !(rect.bottom < 0 || rect.top - viewHeight >= 0);
};

const makeItLoadable = infiniteScroll => {
    if ('true' === infiniteScroll.dataset.visible && !checkVisible(infiniteScroll)) {
        infiniteScroll.dataset.visible = 'false';
    }
    if ('false' === infiniteScroll.dataset.visible && checkVisible(infiniteScroll)) {
        infiniteScroll.dataset.visible = 'true';
    }
};

(() => {
    let fakeHrefs = document.querySelectorAll('.fakeHref');
    if (fakeHrefs) {
        fakeHrefs.forEach(fakeHref => {
            fakeHref.addEventListener('click', () => {
                window.location = fakeHref.dataset.href
            });
        });
    }

    let infiniteScrolls = document.querySelectorAll('.infiniteScroll'),
        xhr = new XMLHttpRequest()
    ;
    if (infiniteScrolls) {
        window.addEventListener('scroll', () => {
            infiniteScrolls.forEach(infiniteScroll => {
                // XHR in progress, stop here
                if (xhr.readyState > 0 && xhr.readyState < 4) {
                    return;
                }

                // no proper dataset, stop here
                if (!infiniteScroll.dataset.visible || !infiniteScroll.dataset.callable || !infiniteScroll.dataset.type || !infiniteScroll.dataset.zapidfrom) {
                    return;
                }

                // Change "visible" dataset for infinite loading elements
                makeItLoadable(infiniteScroll);

                let url = '/zap/infinite/' + infiniteScroll.dataset.type + '/' + infiniteScroll.dataset.zapidfrom;

                // If infinite bloc isn't already working and XHR is unsent
                if ('true' === infiniteScroll.dataset.visible && 'true' === infiniteScroll.dataset.callable) {
                    if (4 === xhr.readyState) {
                        xhr = new XMLHttpRequest()
                        infiniteScroll.dataset.callable = 'false';
                    }

                    if (0 === xhr.readyState) {
                        xhr.responseType = 'json';
                        xhr.onreadystatechange = () => {
                            if (4 === xhr.readyState) {
                                let nextZap = xhr.response[0],
                                    nextZapImage = document.createElement('img')
                                ;
                                if (nextZap) {
                                    nextZapImage.src = 'images/uploads/' + nextZap['thumbnail'];
                                    infiniteScroll.appendChild(nextZapImage);
                                }

                                infiniteScroll.dataset.callable = 'true';
                            }
                        };
                        xhr.open("GET", url, true);
                        xhr.send();
                    }
                }
            });
        });
    }
})();