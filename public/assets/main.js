(() => {
    const applyFakeHref = fakeHref => {
        fakeHref.addEventListener('click', () => {
            window.location = fakeHref.dataset.href
        });
    };

    let fakeHrefs = document.querySelectorAll('.fakeHref');
    if (fakeHrefs) {
        fakeHrefs.forEach(fakeHref => {
            applyFakeHref(fakeHref);
        });
    }

    let infiniteZaps = document.querySelectorAll('.infiniteZap');
    if (infiniteZaps) {
        infiniteZaps.forEach(infiniteZap => {
            let observer = new IntersectionObserver(entries => {
                if (true === entries[0].isIntersecting) {
                    let xhr = new XMLHttpRequest(),
                        nextZap = infiniteZap.previousElementSibling.cloneNode()
                    ;

                    xhr.responseType = 'json';
                    xhr.onreadystatechange = () => {
                        if (4 === xhr.readyState) {
                            if (!xhr.response[0]) {
                                infiniteZap.remove();
                                return;
                            }

                            nextZap.src = 'images/uploads/' + xhr.response[0]['thumbnail'];
                            nextZap.alt = xhr.response[0]['title'];
                            let urlParts = nextZap.dataset.href.split('/');
                            urlParts[urlParts.length - 1] = xhr.response[0]['id'];
                            nextZap.dataset.href = urlParts.join('/');

                            applyFakeHref(nextZap);

                            infiniteZap.parentElement.insertBefore(nextZap, infiniteZap);
                            infiniteZap.dataset.latestzapid = xhr.response[0]['id'];
                        }
                    };
                    xhr.open("GET", '/zap/infinite/' + infiniteZap.dataset.type + '/' + infiniteZap.dataset.latestzapid, true);
                    xhr.send();
                }
            }, {
                threshold: [1]
            });
            observer.observe(infiniteZap);
        });
    }
})();