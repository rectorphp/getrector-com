<style>
    /* Hamburger icon */
    .hamburger {
        display: none; /* Hidden by default */
        flex-direction: column;
        justify-content: space-between;
        width: 30px;
        height: 20px;
        cursor: pointer;
        z-index: 1001;
    }

    .hamburger span {
        width: 100%;
        height: 4px;
        background: #fff; /* Match navbar-dark */
        transition: all 0.3s ease;
    }

    #menu-toggle {
        display: none;
    }

    #menu-toggle:checked + .hamburger span:nth-child(1) {
        transform: rotate(45deg) translate(6px, 6px);
    }

    #menu-toggle:checked + .hamburger span:nth-child(2) {
        opacity: 0;
    }

    #menu-toggle:checked + .hamburger span:nth-child(3) {
        transform: rotate(-45deg) translate(8px, -8px);
    }

    /* Mobile sliding menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background: #343a40; /* Match navbar-dark */
        transition: left 0.3s ease;
        padding-top: 60px;
        z-index: 1000;
    }

    #menu-toggle:checked ~ .mobile-menu {
        left: 0;
    }

    .mobile-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
        margin-top: 1em;
    }

    .mobile-menu li {
        padding: .8em 1.7em;
    }

    .mobile-menu a {
        color: #fff;
        text-decoration: none;
        font-size: 1.3em;
    }

    .mobile-menu a:hover {
        color: #ccc;
    }

    .mobile-menu-logo img {
        max-width: 10em;
        margin-right: 1em;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .hamburger {
            display: flex;
            /*position: absolute;*/
            top: 20px;
            left: 20px;
        }

        .navbar-toggler,
        .navbar-collapse {
            display: none; /* Hide desktop menu and toggler */
        }

        .navbar {
            position: relative;
            top: 0;
            width: 100%;
            z-index: 999;
        }
    }

    /* Desktop styles: hide hamburger and mobile menu */
    @media (min-width: 769px) {
        .mobile-menu-logo,
        .hamburger,
        .mobile-menu,
        #menu-toggle {
            display: none;
        }

        /* Ensure desktop navbar is always expanded */
        .navbar-collapse {
            display: flex !important;
        }
    }
</style>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mobile-fixed-top">
    <div class="container">
        <!-- CSS-only hamburger menu -->
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="hamburger mt-3 mb-3">
            <span></span>
            <span></span>
            <span></span>
        </label>

        <div class="mobile-menu-logo">
            <a href="{{ action(\App\Controller\HomepageController::class) }}">
                <img src="/assets/images/new-logo/smaller/rector-logo-smaller-white.png" alt="Rector logo">
            </a>
        </div>

        <!-- Mobile sliding menu -->
        <div class="mobile-menu">


            <ul>

                @include('_snippets/menu_items')
                <li>
                    <a href="http://github.com/rectorphp/rector" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="30" height="30" viewBox="0 0 30 30">
                            <path d="M15,3C8.373,3,3,8.373,3,15c0,5.623,3.872,10.328,9.092,11.63C12.036,26.468,12,26.28,12,26.047v-2.051 c-0.487,0-1.303,0-1.508,0c-0.821,0-1.551-0.353-1.905-1.009c-0.393-0.729-0.461-1.844-1.435-2.526 c-0.289-0.227-0.069-0.486,0.264-0.451c0.615,0.174,1.125,0.596,1.605,1.222c0.478,0.627,0.703,0.769,1.596,0.769 c0.433,0,1.081-0.025,1.691-0.121c0.328-0.833,0.895-1.6,1.588-1.962c-3.996-0.411-5.903-2.399-5.903-5.098 c0-1.162,0.495-2.286,1.336-3.233C9.053,10.647,8.706,8.73,9.435,8c1.798,0,2.885,1.166,3.146,1.481C13.477,9.174,14.461,9,15.495,9 c1.036,0,2.024,0.174,2.922,0.483C18.675,9.17,19.763,8,21.565,8c0.732,0.731,0.381,2.656,0.102,3.594 c0.836,0.945,1.328,2.066,1.328,3.226c0,2.697-1.904,4.684-5.894,5.097C18.199,20.49,19,22.1,19,23.313v2.734 c0,0.104-0.023,0.179-0.035,0.268C23.641,24.676,27,20.236,27,15C27,8.373,21.627,3,15,3z"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Original desktop navbar (kept untouched) -->
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto text-start text-md-center">
                <li class="nav-item me-0 me-md-3 mb-1 mb-md-0" id="top_right_logo">
                    <a href="{{ action(\App\Controller\HomepageController::class) }}">
                        <img src="/assets/images/new-logo/smaller/rector-logo-smaller-white.png" alt="Rector logo">
                    </a>
                </li>
                @include('_snippets/menu_items')
            </ul>
            <ul class="navbar-nav me-auto text-md-center">
                <li class="nav-item">
                    <a href="http://github.com/rectorphp/rector" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="30" height="30" viewBox="0 0 30 30">
                            <path d="M15,3C8.373,3,3,8.373,3,15c0,5.623,3.872,10.328,9.092,11.63C12.036,26.468,12,26.28,12,26.047v-2.051 c-0.487,0-1.303,0-1.508,0c-0.821,0-1.551-0.353-1.905-1.009c-0.393-0.729-0.461-1.844-1.435-2.526 c-0.289-0.227-0.069-0.486,0.264-0.451c0.615,0.174,1.125,0.596,1.605,1.222c0.478,0.627,0.703,0.769,1.596,0.769 c0.433,0,1.081-0.025,1.691-0.121c0.328-0.833,0.895-1.6,1.588-1.962c-3.996-0.411-5.903-2.399-5.903-5.098 c0-1.162,0.495-2.286,1.336-3.233C9.053,10.647,8.706,8.73,9.435,8c1.798,0,2.885,1.166,3.146,1.481C13.477,9.174,14.461,9,15.495,9 c1.036,0,2.024,0.174,2.922,0.483C18.675,9.17,19.763,8,21.565,8c0.732,0.731,0.381,2.656,0.102,3.594 c0.836,0.945,1.328,2.066,1.328,3.226c0,2.697-1.904,4.684-5.894,5.097C18.199,20.49,19,22.1,19,23.313v2.734 c0,0.104-0.023,0.179-0.035,0.268C23.641,24.676,27,20.236,27,15C27,8.373,21.627,3,15,3z"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
