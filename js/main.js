document.addEventListener('DOMContentLoaded', function () {
    // 1) Register form validation (password match)
    const registerForm = document.querySelector('#registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const pass = document.querySelector('#password');
            const confirm = document.querySelector('#confirm_password');

            if (pass.value.length < 6) {
                alert('Password must be at least 6 characters.');
                e.preventDefault();
                return;
            }

            if (pass.value !== confirm.value) {
                alert('Password and Confirm Password do not match.');
                e.preventDefault();
            }
        });
    }

    // 2) Booking form validation (end time > start time)
    const bookingForm = document.querySelector('#bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            const startTime = document.querySelector('#start_time').value;
            const endTime = document.querySelector('#end_time').value;

            if (startTime && endTime && endTime <= startTime) {
                alert('End time must be greater than start time.');
                e.preventDefault();
            }
        });
    }

    // 3) Confirm dialog for important actions (approve / reject / delete / cancel)
    const confirmLinks = document.querySelectorAll('[data-confirm]');
    confirmLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const msg = this.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // 4) Manager dashboard card entrance animation
    const managerCard = document.querySelector('#viewRequestsCard');
    if (managerCard) {
        // small delay for animation smooth 
        setTimeout(() => {
            managerCard.classList.add('is-visible');
        }, 150);
    }

    // 5) Admin dashboard cards entrance animation
    const adminCards = document.querySelectorAll('.admin-card');
    if (adminCards.length) {
        adminCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('is-visible');
            }, 150 + index * 80); // little stagger animation
        });
    }
});




// About page scroll animation (fade-in on scroll)
document.addEventListener('DOMContentLoaded', function () {
    const revealBlocks = document.querySelectorAll('.about-reveal');

    if (!revealBlocks.length) return;

    // old browser er jonno fallback
    if (!('IntersectionObserver' in window)) {
        revealBlocks.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.2 }
    );

    revealBlocks.forEach(el => observer.observe(el));
});
