document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.querySelector('.preloader');
    window.addEventListener('load', function() {
        setTimeout(function() {
            preloader.classList.add('hidden');
        }, 500);
    });
    const header = document.querySelector('.futuristic-header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    const gameCards = document.querySelectorAll('.game-card');
    gameCards.forEach(card => {
        const moreInfoBtn = card.querySelector('.btn-more-info');
        const reviewsBtn = card.querySelector('.btn-reviews');
        const detailsSection = card.querySelector('.game-details');
        const commentsSection = card.querySelector('.game-comments');
        if (moreInfoBtn) {
            moreInfoBtn.addEventListener('click', function() {
                const isExpanded = this.getAttribute('data-expanded') === 'true';
                if (commentsSection && commentsSection.classList.contains('active')) {
                    commentsSection.classList.remove('active');
                    reviewsBtn.setAttribute('data-expanded', 'false');
                    reviewsBtn.innerHTML = '<i class="fas fa-comment"></i><span>Reviews (' + 
                        card.querySelectorAll('.comment-item').length + ')</span>';
                }
                if (isExpanded) {
                    detailsSection.classList.remove('active');
                    this.setAttribute('data-expanded', 'false');
                    this.innerHTML = '<i class="fas fa-chevron-down"></i><span>More Info</span>';
                } else {
                    detailsSection.classList.add('active');
                    this.setAttribute('data-expanded', 'true');
                    this.innerHTML = '<i class="fas fa-chevron-up"></i><span>Less Info</span>';
                }
            });
        }
        if (reviewsBtn) {
            reviewsBtn.addEventListener('click', function() {
                const isExpanded = this.getAttribute('data-expanded') === 'true';
                if (detailsSection && detailsSection.classList.contains('active')) {
                    detailsSection.classList.remove('active');
                    moreInfoBtn.setAttribute('data-expanded', 'false');
                    moreInfoBtn.innerHTML = '<i class="fas fa-chevron-down"></i><span>More Info</span>';
                }
                if (isExpanded) {
                    commentsSection.classList.remove('active');
                    this.setAttribute('data-expanded', 'false');
                    this.innerHTML = '<i class="fas fa-comment"></i><span>Reviews (' + 
                        card.querySelectorAll('.comment-item').length + ')</span>';
                } else {
                    commentsSection.classList.add('active');
                    this.setAttribute('data-expanded', 'true');
                    this.innerHTML = '<i class="fas fa-comment"></i><span>Hide Reviews</span>';
                }
            });
        }
        const starsContainer = card.querySelector('.stars');
        if (starsContainer) {
            const stars = starsContainer.querySelectorAll('i');
            const ratingText = card.querySelector('.rating-text');
            let selectedRating = 0;
            stars.forEach((star, index) => {
                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('hover');
                        } else {
                            s.classList.remove('hover');
                        }
                    });
                });
                star.addEventListener('mouseleave', function() {
                    stars.forEach(s => s.classList.remove('hover'));
                });
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach((s, i) => {
                        if (i < selectedRating) {
                            s.classList.add('active');
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('active');
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                    if (ratingText) {
                        ratingText.textContent = `Your rating: ${selectedRating}/5`;
                    }
                    const commentInput = card.querySelector('.comment-input');
                    const submitBtn = card.querySelector('.btn-submit-comment');
                    if (commentInput && submitBtn) {
                        submitBtn.disabled = !(selectedRating > 0 && commentInput.value.trim() !== '');
                    }
                });
            });
            const commentInput = card.querySelector('.comment-input');
            const submitBtn = card.querySelector('.btn-submit-comment');
            if (commentInput && submitBtn) {
                commentInput.addEventListener('input', function() {
                    submitBtn.disabled = !(selectedRating > 0 && this.value.trim() !== '');
                });
            }
            const commentForm = card.querySelector('.comment-form');
            if (commentForm) {
                commentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (selectedRating === 0 || !commentInput || commentInput.value.trim() === '') {
                        return;
                    }
                    const gameId = this.getAttribute('data-game-id');
                    const commentText = commentInput.value.trim();
                    const commentsList = card.querySelector('.comments-list');
                    const noComments = card.querySelector('.no-comments');
                    if (noComments) {
                        noComments.remove();
                    }
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    const now = new Date();
                    const formattedDate = now.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                    newComment.innerHTML = `
                        <div class="comment-header">
                            <span class="comment-author">You</span>
                            <div class="comment-rating">
                                ${Array(5).fill(0).map((_, i) => 
                                    `<i class="fas fa-star ${i < selectedRating ? 'active' : ''}"></i>`
                                ).join('')}
                            </div>
                        </div>
                        <p class="comment-text">${commentText}</p>
                        <div class="comment-date">${formattedDate}</div>
                    `;
                    if (commentsList) {
                        commentsList.insertBefore(newComment, commentsList.firstChild);
                    }
                    commentInput.value = '';
                    selectedRating = 0;
                    stars.forEach(s => {
                        s.classList.remove('active');
                        s.classList.remove('fas');
                        s.classList.add('far');
                    });
                    if (ratingText) {
                        ratingText.textContent = 'Rate this game';
                    }
                    submitBtn.disabled = true;
                    const reviewsCount = card.querySelectorAll('.comment-item').length;
                    if (reviewsBtn.getAttribute('data-expanded') !== 'true') {
                        reviewsBtn.innerHTML = `<i class="fas fa-comment"></i><span>Reviews (${reviewsCount})</span>`;
                    }
                });
            }
        }
    });
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
});
function initParticles() {
    if (typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: "#6e00ff"
                },
                shape: {
                    type: "circle",
                    stroke: {
                        width: 0,
                        color: "#000000"
                    },
                },
                opacity: {
                    value: 0.5,
                    random: true,
                },
                size: {
                    value: 3,
                    random: true,
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#6e00ff",
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: "none",
                    random: true,
                    straight: false,
                    out_mode: "out",
                    bounce: false,
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "grab"
                    },
                    onclick: {
                        enable: true,
                        mode: "push"
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 1
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    }
}
