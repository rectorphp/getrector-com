@extends('base')

@section('main')
    <div id="interactive">
        <h1 class="main-title">
            Play and Learn
        </h1>

        <p>
            Dive into the world of coding with our interactive tools. Play and learn as you explore
            the abstract syntax tree (AST) and nodes visually. Rector is as powerful as your
            understanding of the AST, making real-time transformations and instant modifications
            easy and fun.
        </p>

        <div class="row">
            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h3>
                    <em class="fas fa-hand-pointer fa-2x fw-fw text-rector-green"></em>
                    &nbsp;
                    Interactive AST
                </h3>

                <img src="/assets/images/homepage/interactive-ast.jpg" class="rounded-4 mb-3 mt-2"
                     style="max-width: 100%;">

                <p>
                    Unlock the power of real-time code parsing to AST. With Interactive AST, see the
                    nodes instantly and elevate your learning efficiency.
                </p>

                <div class="text-center mt-5">
                    <a
                            href="{{ action(\Rector\Website\Ast\Controller\AstController::class) }}"
                            class="btn btn-success btn-lg mt-auto"
                    >
                        Try Interactive AST
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h3>
                    <em class="fas fa-magnifying-glass fa-2x fa-fw text-rector-green"></em>
                    &nbsp;
                    Find the right Rule
                </h3>

                <img src="/assets/images/homepage/find.jpg"
                     class="rounded-4 mb-3 mt-2 border-1 border border-dark"
                     style="max-width: 100%;">

                <p>
                    Accelerate your coding workflow by quickly finding the right rule. Our smart
                    search feature ensures you have the best solutions at your fingertips, quickly.
                </p>

                <div class="text-center mt-5">
                    <a
                            href="{{ action(\Rector\Website\Controller\FilterRectorController::class) }}"
                            class="btn btn-success btn-lg mt-auto"
                    >
                        Find the Rule
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h3>
                    <em class="fas fa-bolt fa-2x fa-fw text-rector-green"></em>
                    &nbsp;
                    Node Instant Modify
                </h3>

                <img src="/assets/images/homepage/instant-speed.jpg" class="rounded-4 mb-3 mt-2"
                     style="max-width: 100%;">

                <p>
                    Play around with AST node effortlessly! With Node Instant Modify, tweak nodes
                    instantly and see the magic happen. Learning nodes has never been this fun and
                    efficient.
                </p>

                <div class="text-center mt-5">
                    <a
                            class="btn btn-light btn-lg mt-auto border disabled"

                    >
                        Coming soon
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
