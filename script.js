
document.addEventListener('DOMContentLoaded', function() {
    const scrollToSection = (event) => {
        event.preventDefault();
        const targetId = event.currentTarget.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    };

    const links = document.querySelectorAll('.header-navigation a');
    links.forEach(link => link.addEventListener('click', scrollToSection));
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("signupForm").addEventListener("submit", function(event) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        const birthdate = new Date(document.getElementById("birthdate").value);
        const today = new Date();
        const age = today.getFullYear() - birthdate.getFullYear();
        const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
        const uppercasePattern = /[A-Z]/;

        let errorMessage = "";

        if (age < 18) {
            errorMessage += "You must be 18 years old or older to sign up.<br>";
        }

        if (password.length < 8 || !specialCharPattern.test(password) || !uppercasePattern.test(password)) {
            errorMessage += "Password must be at least 8 characters long, contain at least 1 special character, and include at least 1 capital letter.<br>";
        }

        if (password !== confirmPassword) {
            errorMessage += "Password and Confirm Password do not match.<br>";
        }

        if (errorMessage) {
            event.preventDefault(); // Prevent the form from submitting
            document.getElementById("errorMessages").innerHTML = errorMessage; // Show error messages
        }
    });
});



 // Function to add or remove the scrolled class based on scroll position
 function handleScroll() {
    const header = document.querySelector('.header');
    const headerButtons = document.querySelectorAll('.header-button');
    const sections = document.querySelectorAll('section');
    const scrollPos = window.scrollY;

    // Change header background color based on scroll position
    if (scrollPos > 50) { // Adjust this value as needed
        header.classList.add('header-scrolled');
    } else {
        header.classList.remove('header-scrolled');
    }

    // Highlight the active button based on scroll position
    let index = sections.length; // Default to the last section
    while (--index && scrollPos + 50 < sections[index].offsetTop) {}

    headerButtons.forEach(button => button.classList.remove('active'));
    if (headerButtons[index]) {
        headerButtons[index].classList.add('active');
    }
}

// Add scroll event listener
window.addEventListener('scroll', handleScroll);

// Initial check in case the user is already scrolled down
handleScroll();
