// Slider functionality
let currentSlide = 0;
const cardsWrapper = document.querySelector('.cards-wrapper');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');
const cards = document.querySelectorAll('.cards-wrapper .hotel-card');

function getVisibleCards() {
    if (window.innerWidth <= 480) return 1;
    if (window.innerWidth <= 768) return 2;
    return 3;
}

function updateSlider() {
    const visibleCards = getVisibleCards();
    const cardWidth = cards[0].offsetWidth;
    const gap = 20;
    const translateX = -currentSlide * (cardWidth + gap);
    cardsWrapper.style.transform = `translateX(${translateX}px)`;
    cardsWrapper.style.transition = 'transform 0.3s ease';
}

if (nextBtn) {
    nextBtn.addEventListener('click', function() {
        const visibleCards = getVisibleCards();
        const maxSlide = cards.length - visibleCards;
        if (currentSlide < maxSlide) {
            currentSlide++;
            updateSlider();
        }
    });
}


if (prevBtn) {
    prevBtn.addEventListener('click', function() {
        if (currentSlide > 0) {
            currentSlide--;
            updateSlider();
        }
    });
}

window.addEventListener('resize', function() {
    updateSlider();
});


const navLinks = document.querySelectorAll('.main-nav a');
navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
            targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});


const searchInput = document.querySelector('.search-input');
const searchBtn = document.querySelector('.search-btn');

if (searchBtn) {
    searchBtn.addEventListener('click', function() {
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            console.log('Searching for:', searchValue);
            // ìm kiếm
            alert('Đang tìm kiếm: ' + searchValue);
        }
    });
}

if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
}

const viewMoreBtn = document.querySelector('.view-more-btn');
if (viewMoreBtn) {
    viewMoreBtn.addEventListener('click', function() {

        alert('Xem thêm khách sạn...');
    });
}

const hotelCards = document.querySelectorAll('.hotel-card');
hotelCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 5px 20px rgba(0,0,0,0.2)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 3px 10px rgba(0,0,0,0.1)';
    });
});


const destinationCards = document.querySelectorAll('.destination-card');
destinationCards.forEach(card => {
    card.addEventListener('click', function() {
        const destination = this.querySelector('.destination-label').textContent;
        console.log('Selected destination:', destination);

        alert('Bạn đã chọn: ' + destination);
    });
});

const header = document.querySelector('.header');
window.addEventListener('scroll', function() {
    if (window.scrollY > 100) {
        header.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
    } else {
        header.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
    }
});

const userIcon = document.querySelector('.user-icon');
if (userIcon) {
    userIcon.addEventListener('click', function() {
        console.log('User icon clicked');
        alert('Tài khoản người dùng');
    });
}

const languageSelector = document.querySelector('.language');
if (languageSelector) {
    languageSelector.addEventListener('click', function() {
        if (this.textContent === 'VN') {
            this.textContent = 'EN';
        } else {
            this.textContent = 'VN';
        }
    });
}


const viewNowBtns = document.querySelectorAll('.view-now-btn');
viewNowBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const hotelName = this.closest('.hotel-card').querySelector('h3').textContent;
        console.log('Viewing hotel:', hotelName);

        alert('Xem chi tiết: ' + hotelName);
    });
});

const socialLinks = document.querySelectorAll('.social-links a');
socialLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Social link clicked');
    });
});

const footerLinks = document.querySelectorAll('.footer-section a');
footerLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const linkText = this.textContent;
        console.log('Footer link clicked:', linkText);

    });
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Travel website loaded');
    updateSlider();
    const elements = document.querySelectorAll('.hotel-card, .destination-card');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

const images = document.querySelectorAll('img');
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
           
            
            img.classList.add('loaded');
            observer.unobserve(img);
        }
    });
});

images.forEach(img => {
    imageObserver.observe(img);
});