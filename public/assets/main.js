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
                        targetUrl = infiniteData.dataset.targeturl,
                        xhrUrl = infiniteData.dataset.xhrurl
                    console.log(infiniteData.dataset.xhrurl)
                    xhr.responseType = 'json'
                    xhr.onreadystatechange = () => {
                        if (4 === xhr.readyState) {
                            // No results - remove infinite loader
                            if (0 === xhr.response.length) {
                                infiniteData.remove()
                                return
                            }

                            xhr.response.forEach(element => {
                                // Build target url
                                targetUrl = targetUrl.replace('0', element.id)

                                // Create link to loaded element
                                let a = document.createElement('a')
                                a.href = targetUrl

                                // Create image to show element
                                let img = document.createElement('img')
                                img.src = infiniteData.dataset.imagepath + element.image

                                // Add element image under element link
                                a.appendChild(img)

                                // Insert element link to DOM
                                infiniteData.parentElement.insertBefore(a, infiniteData)

                                // Update infinite element url for next call
                                let xhrUrlParts = xhrUrl.split('/')
                                xhrUrlParts[xhrUrlParts.length - 1] = element.id
                                infiniteData.dataset.xhrurl = xhrUrlParts.join('/')
                                console.log(xhrUrlParts)
                            })
                        }
                    }
                    xhr.open("GET", infiniteData.dataset.xhrurl, true)
                    xhr.send()
                }
            }, {
                threshold: [1]
            })
            observer.observe(infiniteData)
        })
    }
})()