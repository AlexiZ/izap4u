(() => {
    const applyFakeHref = fakeHref => {
        fakeHref.addEventListener('click', () => {
            window.location = fakeHref.dataset.href
        })
    }

    let fakeHrefs = document.querySelectorAll('.fakeHref')
    if (fakeHrefs) {
        fakeHrefs.forEach(fakeHref => {
            applyFakeHref(fakeHref)
        })
    }

    let infiniteDatas = document.querySelectorAll('.infiniteData')
    if (infiniteDatas) {
        infiniteDatas.forEach(infiniteData => {
            let observer = new IntersectionObserver(entries => {
                if (true === entries[0].isIntersecting) {
                    let xhr = new XMLHttpRequest(),
                        nextData = infiniteData.previousElementSibling.cloneNode()

                    xhr.responseType = 'json'
                    xhr.onreadystatechange = () => {
                        if (4 === xhr.readyState) {
                            if (!xhr.response[0]) {
                                infiniteData.remove()
                                return
                            }

                            nextData.src = infiniteData.dataset.imagepath + xhr.response[0]['image']
                            nextData.alt = xhr.response[0]['title']

                            let urlParts = nextData.dataset.href.split('/')
                            urlParts[urlParts.length - 1] = xhr.response[0]['id']
                            nextData.dataset.href = urlParts.join('/')

                            applyFakeHref(nextData);

                            infiniteData.parentElement.insertBefore(nextData, infiniteData);

                            urlParts = infiniteData.dataset.url.split('/')
                            urlParts[urlParts.length - 1] = xhr.response[0]['id']
                            infiniteData.dataset.url = urlParts.join('/')
                        }
                    }
                    xhr.open("GET", infiniteData.dataset.url, true)
                    xhr.send()
                }
            }, {
                threshold: [1]
            })
            observer.observe(infiniteData)
        })
    }
})();