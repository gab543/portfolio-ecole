export function initNavbar() {
    // Mobile Navbar Burger Menu Logic
    const burgerBtn = document.querySelector('.burger-menu-btn');
    const navbar = document.querySelector('.navbar');
    const title = document.querySelector('.logo a');
    
    console.log("Navbar init loaded - Button:", burgerBtn, "Navbar:", navbar);

    if (burgerBtn && navbar) {
        burgerBtn.addEventListener('click', () => {
            console.log('Burger button clicked!');
            burgerBtn.classList.toggle('active');
            navbar.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
            title.classList.toggle('active');
        });
        
        // Close menu when clicking a link
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                burgerBtn.classList.remove('active');
                navbar.classList.remove('active');
                document.body.classList.remove('no-scroll');
            });
        });
        
        // Scroll effect for logo
        const mainHeader = document.querySelector('.main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                mainHeader.classList.add('scrolled');
            } else {
                mainHeader.classList.remove('scrolled');
            }
        });
    }
}
