<div class="sidebar-wrapper group">
    <div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden">
    </div>
    <div class="logo-segment">
        <a class="flex items-center" href="<?php echo (!empty($title) && $title == 'Dashboard') ? route('admin/dashboard') : 'javascript:void(0)'; ?>">
            {{-- <img src="<?php //echo url('public/assets/images/logo/logo-c.svg'); ?>" class="black_logo" alt="logo">
            <img src="<?php //echo url('public/assets/images/logo/logo-c-white.svg'); ?>" class="white_logo" alt="logo"> --}}
            <img src="<?php echo !empty($admin_detail->logo) ? url('public/assets/admin/adminimages/' . $admin_detail->logo) : url('public/assets/images/logo/logo-c.svg'); ?>" alt="logo">
            <span class="ltr:ml-3 rtl:mr-3 text-xl font-Inter font-bold text-slate-900 dark:text-white logoText">Uncruise</span>
        </a>
        <!-- Sidebar Type Button -->
        <div id="sidebar_type" class="cursor-pointer text-slate-900 dark:text-white text-lg hidden">
            <span class="sidebarDotIcon extend-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
                <div
                    class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150 ring-2 ring-inset ring-offset-4 ring-black-900 dark:ring-slate-400 bg-slate-900 dark:bg-slate-400 dark:ring-offset-slate-700">
                </div>
            </span>
            <span class="sidebarDotIcon collapsed-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
                <div
                    class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150">
                </div>
            </span>
        </div>
        <button class="sidebarCloseIcon text-2xl">
            <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line">
            </iconify-icon>
        </button>
    </div>
    <div id="nav_shadow"
        class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-noneopacity-0">
    </div>
    <div class="sidebar-menus bg-white dark:bg-slate-800 py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50"
        id="sidebar_menus">
        <ul class="sidebar-menu">
            <li class="sidebar-menu-title">MENU</li>
            <li class="">
                <a href="<?php echo route('admin/dashboard'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Dashboard') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class=" nav-icon" icon="heroicons-outline:home"></iconify-icon>
                        <span>Dashboard</span>
                    </span>
                </a>
            </li>
            <!-- Apps Area -->
            <!-- <li class="sidebar-menu-title">COMPONENTS</li> -->
            <li>
                <a href="<?php echo route('admin/users'); ?>" class="navItem <?php echo (!empty($title) && ($title == 'Users' || $title == 'User Details')) ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:user-group"></iconify-icon>
                        <span>Users</span>
                    </span>
                </a>
            </li>
            <!--<li>-->
            <!--    <a href="<?php //echo route('admin/surveys'); ?>" class="navItem <?php //echo (!empty($title) && $title == 'Surveys') ? 'active' : ''; ?>">-->
            <!--        <span class="flex items-center">-->
            <!--            <iconify-icon class="nav-icon" icon="ri:survey-line"></iconify-icon>-->
            <!--            <span>Surveys</span>-->
            <!--        </span>-->
            <!--    </a>-->
            <!--</li>-->
            <li>
                <a href="<?php echo route('admin/cruise'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Cruise' || $title == 'Add Cruise' || $title == 'Edit Cruise') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="tabler:ship"></iconify-icon>
                        <span>Cruise</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/destinations'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Destinations' || $title == 'Add Destination' || $title == 'Edit Destination') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="material-symbols:location-on-outline"></iconify-icon>
                        <span>Destinations</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/adventures'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Adventures' || $title == 'Add Adventure' || $title == 'Edit Adventure') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="gis:earth-atlantic-o"></iconify-icon>
                        <span>Adventures</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/journeys'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Journeys' || $title == 'Add Journey' || $title == 'Edit Journey') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="ic:outline-travel-explore"></iconify-icon>
                        <span>Journeys</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/reviews'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Reviews' || $title == 'Review Details') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="material-symbols:rate-review-outline-rounded"></iconify-icon>
                        <span>User Reviews</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/testimonials'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Testimonials' || $title == 'Testimonial Details') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="bi:chat-quote"></iconify-icon>
                        <span>Post & Testimonials</span>
                    </span>
                </a>
            </li>
            <!-- Pages Area -->
            <li class="sidebar-menu-title">APP MANAGEMENT</li>

            <li>
                <a href="<?php echo route('admin/siteSetting',['key' => 'contact-us']); ?>" class="navItem <?php echo (!empty($title) && $title == 'Contact Us') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon  class=" nav-icon" icon="mdi:about-circle-outline"></iconify-icon>
                        <span>Contact Us</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/siteSetting',['key' => 'terms-condition']); ?>" class="navItem <?php echo (!empty($title) && $title == 'Terms & Condition') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                    <iconify-icon  class="nav-icon" icon="mdi:alpha-t-circle"></iconify-icon>
                        <span>Terms & Conditions</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/siteSetting',['key' => 'privacy-policy']) ?>" class="navItem <?php echo (!empty($title) && $title == 'Privacy Policy') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="iconoir:privacy-policy"></iconify-icon>
                        <span>Privacy Policy</span>
                    </span>
                </a>
            </li>
            <?php //echo route('admin/siteSetting',['key' => 'privacy-policy']); ?>
            <!-- Authentication -->
            <!--<li class="<?php // if((!empty($title)) && ($title == 'Terms & Condition' || $title == 'Privacy Policy' || $title == 'About Us')){echo 'active';} ?>">
                 <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class=" nav-icon" icon="heroicons:rocket-launch"></iconify-icon>
                        <span>Site Settings</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu"> -->
                    <!-- <li>
                        <a href="<?php //echo route('admin/siteSetting',['key' => 'terms-condition']); ?>" class="<?php // if((!empty($title)) && ($title == 'Terms & Condition')){echo 'active';} ?>">
                            Terms of Use
                        </a>
                    </li>
                    <li>
                        <a href="<?php //echo route('admin/siteSetting',['key' => 'privacy-policy']); ?>"class="<?php //if((!empty($title)) && ($title == 'Privacy Policy')){echo 'active';} ?>">
                            Privacy Notice
                        </a>
                    </li>
                    <li>
                        <a href="<?php //echo route('admin/siteSetting',['key' => 'about-us']); ?>"class="<?php //if((!empty($title)) && ($title == 'About Us')){echo 'active';} ?>">
                            About
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?php //echo route('admin/faqs'); ?>" class="navItem <?php //if((!empty($title)) && ($title == 'FAQs')){echo 'active';} ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="mdi:faq"></iconify-icon>
                        <span>FAQ's</span>
                    </span>
                </a>
            </li> -->
            <!-- <li>
                <a href="<?php //echo route('admin/social'); ?>" class="navItem <?php //if((!empty($title)) && ($title == 'Social')){echo 'active';} ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="tabler:social"></iconify-icon>
                        <span>Social Links</span>
                    </span>
                </a>
            </li> -->
            <!-- <li>
                <a href="<?php //echo route('admin/contactDetails'); ?>" class="navItem <?php // if((!empty($title)) && ($title == 'Contact Details')){echo 'active';} ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:chat"></iconify-icon>
                        <span>Contact Details</span>
                    </span>
                </a>
            </li>
        /</ul> -->
    </div>
</div>
