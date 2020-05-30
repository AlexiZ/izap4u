(() => {
    let fakeHrefs = document.querySelectorAll('.fakeHref');
    if (fakeHrefs) {
        fakeHrefs.forEach(fakeHref => {
            fakeHref.addEventListener('click', () => {
                window.location = fakeHref.dataset.href
            });
        });
    }
})();