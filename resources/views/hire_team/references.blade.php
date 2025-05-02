<style>
    /* Carousel container */
    .testimonial-carousel {
        width: 90%;
        max-width: 55.5rem; /* 600px */
        margin: 0 auto;
        overflow: hidden;
        position: relative;
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
    }

    #references .card {
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Navigation buttons */
    .testimonial-carousel__button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 0.8rem 0.9375rem; /* 10px 15px */
        cursor: pointer;
        border-radius: 0.3125rem; /* 5px */
        transition: background 0.3s;
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
    @media (max-width: 400px) {
        .testimonial-carousel {
            padding: 0.625rem; /* 10px */
        }
    }
</style>

<div id="references">
    <div class="testimonial-carousel">
        <div class="testimonial-carousel__track">
            @foreach ($references as $reference)
                <div class="testimonial-carousel__item">
                    <div class="card">
                        <div class="card-body company-quote-card-body" style="display: flex">
                            <div>
                                <img
                                    src="{{ $reference['picture'] }}"
{{--                                    class="testimonial-card__image"--}}
                                    class="rounded-circle img-face-smaller-left me-4"
                                    alt="{{ $reference['name'] }}"
                                >
                            </div>
                            <blockquote class="blockquote company-quote me-4">
                                <p>
                                    {!! $reference['testimonial'] !!}
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
