document.addEventListener('DOMContentLoaded', function() {
    const sectionHeaders = document.querySelectorAll('.footer-section-header');
    const faqLists = document.querySelectorAll('.footer-list');
    const imageIcons = document.querySelectorAll('.section-icon');

    let currentOpenTitle = "";

    function setHeight(element, height) {
        element.style.maxHeight = height;
    }

    function calculateAutoHeight(element) {
        element.style.maxHeight = 'none';
        const height = element.scrollHeight + 'px';
        element.style.maxHeight = '';
        return height;
    }

    function scrollToElement(element) {
        const elementBottom = element.getBoundingClientRect().bottom;
        const viewportHeight = window.innerHeight;
        if (elementBottom > viewportHeight) {
            window.scrollBy({
                top: elementBottom - viewportHeight + window.scrollY,
                behavior: 'smooth'
            });
        }
    }

    sectionHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            faqLists.forEach(function(list) {
                setHeight(list, "0px");
            });

            imageIcons.forEach(function(icon) {
                icon.style.transform = "rotate(-90deg)";
            });

            const answer = header.nextElementSibling;
            const icon = header.querySelector('.section-icon');

            if (header.querySelector('.section-title')?.textContent === currentOpenTitle) {
                if (answer) {
                    setHeight(answer, "0px");
                }
                if (icon) {
                    icon.style.transform = "rotate(-90deg)";
                }
                currentOpenTitle = "";
                return;
            }

            if (answer) {
                const fullHeight = calculateAutoHeight(answer);
                setHeight(answer, fullHeight);
                scrollToElement(answer);
            }
            if (icon) {
                icon.style.transform = "rotate(0deg)";
            }
            currentOpenTitle = header.querySelector('.section-title')?.textContent || "";
        });
    });
});
