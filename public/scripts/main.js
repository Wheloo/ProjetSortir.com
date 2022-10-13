/*const navigation = document.querySelector('nav');

window.addEventListener('scroll', () => {
    if(window.scrollY > 30) {
        navigation.classList.add('anim-navbar');
    } else {
        navigation.classList.remove('anim-navbar');
    }
})*/

/*function couleur() {
    const elt=document.getElementById('filtration');
    elt.style.backgroundColor='#f1f1f1';
    setTimeout(function () {
        elt.style.backgroundColor='#EEEEEEAA';
    }, 2000)
}*/

const ratio = .1
const options = {
    root : null,
    rootMargin: '0px',
    threshold: ratio
}

const handleIntersect = function (entries, observer) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('reveal-visible')
            observer.unobserve(entry.target)
        }
    })
}

const observer = new IntersectionObserver(handleIntersect, options)
document.querySelectorAll('[class*="reveal-"]').forEach(function (r) {
    observer.observe(r)
})

