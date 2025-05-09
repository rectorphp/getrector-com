<style>
    .faq-container {
        max-width: 50em;
        margin:auto;
    }

    .faq-item {
        border-bottom: 1px solid #ddd;
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 300;
        font-size: 1.7em;
    }

    .faq-answer {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        padding: 0;
        color: #34495e;
        transition: max-height 0.5s ease, opacity 0.5s ease, padding 0.5s ease;
    }

    .faq-answer.open {
        max-height: 200px; /* Adjust based on content size */
        opacity: 1;
        padding: 10px 0;
    }

    .faq-toggle {
        font-size: 1.5em;
        font-weight: 200;
        /*font-weight: bold;*/
        transition: transform 0.5s ease;
        color: #aaa;
    }

    .faq-toggle::before {
        content: '\2715'; /* Unicode for cross (×) */
        display: inline-block;
    }

    .faq-toggle.open {
        transform: rotate(45deg);
    }
</style>

<div class="faq-container" id="faq">
    @foreach($faqs as $faq)
        <div class="faq-item">
            <h3 class="faq-question">
                <span>{!! $faq['question'] !!}</span>
                <span class="faq-toggle"></span>
            </h3>
            <p class="faq-answer">
                {{ $faq['answer'] }}
            </p>
        </div>
    @endforeach
</div>


<script>
    document.querySelectorAll('.faq-question').forEach(item => {
        item.addEventListener('click', () => {
            const answer = item.nextElementSibling;
            const toggle = item.querySelector('.faq-toggle');

            answer.classList.toggle('open');
            toggle.classList.toggle('open');
        });
    });
</script>
