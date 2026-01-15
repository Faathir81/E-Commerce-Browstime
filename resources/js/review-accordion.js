document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.review-accordion').forEach((accordion) => {
        const summary = accordion.querySelector('summary');
        const content = accordion.querySelector('.accordion-content');
        if (!summary || !content) return;

        const setState = (open, animate = true) => {
            const startHeight = content.getBoundingClientRect().height;
            const endHeight = open ? content.scrollHeight : 0;

            if (!animate) {
                content.style.maxHeight = open ? 'none' : '0px';
                content.style.opacity = open ? '1' : '0';
                content.style.overflow = open ? 'visible' : 'hidden';
                accordion.toggleAttribute('open', open);
                return;
            }

            content.style.overflow = 'hidden';
            content.style.maxHeight = `${startHeight}px`;

            requestAnimationFrame(() => {
                accordion.toggleAttribute('open', open);
                content.style.maxHeight = `${endHeight}px`;
                content.style.opacity = open ? '1' : '0';
            });

            const onEnd = () => {
                content.style.overflow = open ? 'visible' : 'hidden';
                if (open) {
                    content.style.maxHeight = 'none';
                }
                content.removeEventListener('transitionend', onEnd);
            };
            content.addEventListener('transitionend', onEnd);
        };

        summary.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = accordion.hasAttribute('open');
            setState(!isOpen);
        });

        // Initialize state (respect server-rendered "open")
        setState(accordion.hasAttribute('open'), false);
    });
});
