<div class="row" id="faq">
    <div class="col-12">
        <h3>Can Rector upgrade PHP 5.3 code?</h3>
        <p>Yes, Rector handles upgrades from PHP 5.3 up to PHP 8.3.</p>
    </div>

    <br>

    <div class="col-12">
        <h3>How long does a typical upgrade take?</h3>
        <p>Every project is different. The project size, code quality, test and type coverage
            affect the final time line.
            <br>
            To give you a rough idea, most project upgrades take between <strong>6 and 12 months</strong>.</p>
    </div>

    <br>

    <div class="col-12">
        <h3>We're in a hurry. Can you start tomorrow?</h3>
        <p>
            First, we do an <a
                    href="{{ action(\Rector\Website\Http\Controllers\ProjectTimelineController::class) }}">intro
                analysis</a> of your project that takes 3 weeks. After that, we can jump right into
            the upgrade work itself.
        </p>

    </div>

    <br>

    <div class="col-12">
        <h3>We have a dozen of projects. Do you have to make an intro analysis on each of them?</h3>
        <p>
            No. Typically we do an analysis on the biggest project and start the upgrade. Then we help you to propagate changes to the other projects.
        </p>

    </div>

    <br>

    <div class="col-12">

        <h3>Do we have to re-hire your team yearly to handle future upgrades?</h3>
        <p>
            No. Our main goal is to help you to become self-sustainable and adaptable for future.
            <br>
            We improve your code quality, test coverage, type coverage and PHPStan level to the highest possible level.
            <br>
            <br>
            <strong>When we finish, you'll have Rector in your CI working for you.</strong>
            Your next upgrade will take a single day.
        </p>
    </div>

    <br>

    <div class="col-12">
        <h3>We want to upgrade, but can't afford to stop new features for a few months.</h3>
        <p>
            We know the business must grow. Stopping development, even for a month, would slow down
            the project and give the competition a huge advantage.
        </p>
        <p>
            That's why we <strong>work in parallel to your project</strong>. Our work flows
            standalone along with your business development. Your team can safely develop features
            as we slowly and safely upgrade your code.
        </p>
    </div>

    <br>

    <div class="col-12">
        <h3>Can you migrate our framework to an open-source one?</h3>
        <p>Yes, that's a field where Rector really shines. The PHP frameworks are very similar to
            each other; they use the MVC pattern. The framework migration is a matter of writing
            30-50 custom Rector rules. We have deep experience with that.</p>

    </div>

    <br>

    <div class="col-12">
        <h3>Our last upgrade took 12 months with no result. How do you help us to avoid it?</h3>
        <p>
            The art of cost-effective upgrades takes years of experience with dozens of projects.
        </p>
        <p>
            Our code changes are small, isolated and to the point. We create <strong>minimalistic
                pull-requests with 1 change, and send it for a review</strong> and you merge it the
            same or next day. That way your project will gain value every day.
        </p>
    </div>
</div>
