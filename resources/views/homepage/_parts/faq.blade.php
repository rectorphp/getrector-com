<div class="row">
    <div class="col-12 col-md-6 mb-4">
         <h3>Can Rector upgrade PHP 5.3 code?</h3>
         <p>Yes, Rector handles upgrades from PHP 5.3 up to PHP 8.2.</p>
    </div>
    <div class="col-12 col-md-6 mb-4">
        <h3>Can you upgrade Javascript or Java?</h3>
        <p>
            No, we can help you with PHP upgrades only.
        </p>
    </div>

    <div class="col-12 col-md-6 mb-4">
         <h3>How long does a typical upgrade take?</h3>
         <p>Every project is different. The project size, code quality, test coverage, type coverage and CI setup affect the project upgrade duration. But to give you a rough idea, most project upgrades we handle take between 6 and 12 months.</p>
    </div>
    <div class="col-12 col-md-6 mb-4">
         <h3>We're in a hurry. How fast can you start upgrade of our project?</h3>
         <p>
             First, we do an <a href="{{ action(\App\Http\Controllers\ForCompaniesController::class) }}">intro analysis</a> of your project that takes 3 weeks. After that, we can jump right into the upgrade work itself.
         </p>

    </div>
    <div class="col-12 col-md-6 mb-4">

         <h3>Do we have to hire you every 2 years to handle the upgrade?</h3>
         <p>
             No. Our goal is not to just simply change the value in <code>composer.json</code> to the latest PHP. We improve your code quality to the highest possible level, we improve test coverage, type coverage and get PHPStan to level 8. We upgrade your CI to catch most bugs long before merge.
             <br><br>
             <strong>When we finish, you'll have Rector in your CI setup working seamlessly.</strong> Your next PHP upgrade will be a matter of changing a single line in the configuration that <strong>you can handle yourself</strong>.
         </p>
     </div>

     <div class="col-12 col-md-6 mb-4">
         <h3>We want to upgrade, but can't afford to stop development even for a few months.</h3>
         <p>
             We know the business must grow. Stopping development, even for a month, would slow down the project and give the competition a huge advantage.
         </p>
         <p>
             That's why we <strong>work in parallel to your project</strong>. Our work flows standalone along with your business development. Your team can safely develop features as we slowly and safely upgrade your code.
         </p>
     </div>
     <div class="col-12 col-md-6 mb-4">
         <h3>Can you migrate our framework to an open-source one?</h3>
         <p>Yes, that's a field where Rector really shines. The PHP frameworks are very similar to each other; they use the MVC pattern. The framework migration is a matter of writing 30-50 custom Rector rules. We have deep experience with that.</p>

     </div>
    <div class="col-12 col-md-6 mb-4">
        <h3>We had a bad experience with an upgrade. It costs us 6 months with no result.<br>How do help us to avoid it?</h3>
        <p>
             Doing upgrades effectively and with confidence takes years of experience with dozens of project.
        </p>
        <p>
             Our code changes are small, isolated and to the point. We create <strong>minimalistic pull-requests with 1 change, and send it for a review</strong> and you merge it the same or next day. That way your project will gain value every day.
        </p>
     </div>
</div>
