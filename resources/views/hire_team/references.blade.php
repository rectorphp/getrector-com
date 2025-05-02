{{-- gpt ref https://grok.com/chat/082afba8-d9c2-42fc-ba27-58a26cb10469 --}}

<style>
    /* Carousel container */
    .testimonial-carousel {
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        padding-bottom: 1em;
    }

    .testimonial-carousel__track {
        display: flex;
        transition-property: transform;
        transition-duration: .5s;
        transition-timing-function: ease-in-out;
        align-items: flex-start;
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

    /* Navigation buttons */
    .testimonial-carousel__button {
        position: absolute;
        top: 43%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: .5rem 1rem .7rem 1rem;
        cursor: pointer;
        border-radius: 0.3125rem; /* 5px */
        transition: background 0.3s;
    }

    .company-quote-card-body {
        display: flex;
        padding: 1.5em 1em 0 2em;
    }

    .testimonial-carousel__button:hover,
    .testimonial-carousel__button:focus {
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
                        <div>
                            <img
                                src="{{ $reference['picture'] }}"
                                class="rounded-circle img-face-smaller-left me-4 mb-3 mb-sm-0"
                                alt="{{ $reference['name'] }}"
                            >
                        </div>
                        <blockquote class="blockquote company-quote me-4">
                            <p>
                                "{!! $reference['testimonial'] !!}"
                            </p>

                            <footer class="blockquote-footer mt-1">
                                {{ $reference['name'] }}, {{ $reference['position'] }}
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button class="testimonial-carousel__button testimonial-carousel__button--prev" onclick="moveSlide(-1)">←</button>
    <button class="testimonial-carousel__button testimonial-carousel__button--next" onclick="moveSlide(1)">→</button>
</div>


<script type="text/javascript">
    let currentIndex = 0;
    const items = document.querySelectorAll('.testimonial-carousel__item');
    const totalItems = items.length;

    function moveSlide(direction) {
        currentIndex += direction;
        if (currentIndex < 0) {
            currentIndex = 0;
        } else if (currentIndex >= totalItems) {
            currentIndex = totalItems - 1;
        }
        const offset = -currentIndex * 100;
        document.querySelector('.testimonial-carousel__track').style.transform = `translateX(${offset}%)`;
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') {
            moveSlide(-1);
        } else if (event.key === 'ArrowRight') {
            moveSlide(1);
        }
    });
</script>
