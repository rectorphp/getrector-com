{{-- gpt ref https://grok.com/chat/082afba8-d9c2-42fc-ba27-58a26cb10469 --}}

<style>
    /* Carousel container */
    .testimonial-carousel {
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        padding-bottom: 1em;
        max-width: 50em;
        user-select: none; /* Prevent text selection during drag */
    }

    .testimonial-carousel__track {
        display: flex;
        transition-property: transform;
        transition-duration: .5s;
        transition-timing-function: ease-in-out;
    }

    .testimonial-carousel__item {
        flex: 0 0 100%;
        box-sizing: border-box;
        padding: 0.9375rem; /* 15px */
        display: flex;
        justify-content: center;
        height: auto;
    }

    .testimonial-carousel__item .card {
        width: 100%;
        max-width: 56rem;
    }

    .company-quote-card-body {
        padding: 1.5em 1em 0 2em;
    }

    .profile-section {
        display: flex;
        margin-top: 2em; /* Reduced from mt-5 for better spacing */
    }

    .profile-image {
        height: 5em;
        border-radius: 50%;
        margin: 0 1em 1em 0; /* Adjusted to remove extra bottom margin */
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .profile-info .name {
        font-size: 1.5em;
    }

    .testimonial-carousel__button {
        text-align: right;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
        width: 2.5rem; /* Circular button size */
        height: 2.5rem;
        border-radius: 50%; /* Make buttons circular */
        display: flex;
        align-items: center;
        justify-content: center;
        position: static; /* Remove absolute positioning */
        margin: 1rem .3em auto; /* Add spacing between buttons and above */
    }

    .testimonial-carousel__button svg {
        width: 1.2rem;
        height: 1.2rem;
        fill: white;
    }

    .testimonial-carousel__button:hover, .testimonial-carousel__button:focus {
        background: rgba(0, 0, 0, 0.8);
        outline: none;
    }

    .testimonial-carousel__button--prev {
        left: 0.625rem; /* 10px */
    }

    .testimonial-carousel__button--next {
        right: 0.625rem; /* 10px */
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
        .testimonial-carousel {
            padding: 0.625rem; /* 10px */
        }

        .company-quote-card-body {
            display: block;
        }
    }
</style>


<div id="references" class="testimonial-carousel">
    <div class="testimonial-carousel__track">
        @foreach ($references as $reference)
            <div class="testimonial-carousel__item">
                <div class="card shadow mt-3 pb-2">
                    <div class="card-body company-quote-card-body">
                        <blockquote class="blockquote company-quote me-4">
                            <p>
                                "{!! $reference['testimonial'] !!}"
                            </p>
                        </blockquote>

                        <div class="profile-section">
                            <img
                                src="{{ $reference['picture'] }}"
                                class="rounded-circle profile-image"
                                alt="{{ $reference['name'] }}"
                            >

                            <div class="profile-info">
                                <div class="name">
                                    {{ $reference['name'] }}
                                </div>

                                <div class="text-secondary position">
                                    {{ $reference['position'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center d-flex ms-2">
        <button class="testimonial-carousel__button testimonial-carousel__button--prev" onclick="moveSlide(-1)">
            <svg viewBox="0 0 24 24">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
        </button>
        <button class="testimonial-carousel__button testimonial-carousel__button--next" onclick="moveSlide(1)">
            <svg viewBox="0 0 24 24">
                <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
            </svg>
        </button>
    </div>
</div>


<script type="text/javascript">
    let currentIndex = 0;
    const track = document.querySelector('.testimonial-carousel__track');
    const items = document.querySelectorAll('.testimonial-carousel__item');
    const totalItems = items.length;

    // Button slide movement
    function moveSlide(direction) {
        currentIndex += direction;
        if (currentIndex < 0) {
            currentIndex = 0;
        } else if (currentIndex >= totalItems) {
            currentIndex = totalItems - 1;
        }
        updateCarousel();
    }

    // Update carousel position
    function updateCarousel() {
        const offset = -currentIndex * 100;
        track.style.transition = 'transform 0.5s ease-in-out';
        track.style.transform = `translateX(${offset}%)`;
    }
</script>
