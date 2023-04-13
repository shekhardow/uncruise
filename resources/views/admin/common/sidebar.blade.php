<div class="sidebar-wrapper group">
    <div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden">
    </div>
    <div class="logo-segment">
        <a class="flex items-center" href="<?php echo route('admin/dashboard'); ?>">
            <img src="<?php echo url('public/assets/images/logo/logo-c.svg'); ?>" class="black_logo" alt="logo">
            <img src="<?php echo url('public/assets/images/logo/logo-c-white.svg'); ?>" class="white_logo" alt="logo">
            <span class="ltr:ml-3 rtl:mr-3 text-xl font-Inter font-bold text-slate-900 dark:text-white">Uncruise</span>
        </a>
        <!-- Sidebar Type Button -->
        <div id="sidebar_type" class="cursor-pointer text-slate-900 dark:text-white text-lg">
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
            <li class="sidebar-menu-title">COMPONENTS</li>
            <li>
                <a href="<?php echo route('admin/users'); ?>" class="navItem <?php echo (!empty($title) && ($title == 'Users' || $title == 'User Details')) ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:user-group"></iconify-icon>
                        <span>Users</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo route('admin/surveys'); ?>" class="navItem <?php echo (!empty($title) && $title == 'Surveys') ? 'active' : ''; ?>">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="ri:survey-fill"></iconify-icon>
                        <span>Surveys</span>
                    </span>
                </a>
            </li>
            <!-- Pages Area -->
            <li class="sidebar-menu-title">APP MANAGEMENT</li>
            <!-- Authentication -->
            <li class="">
                <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class=" nav-icon" icon="heroicons:rocket-launch"></iconify-icon>
                        <span>Site Settings</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="javascript:void(0)">Terms of Use</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Privacy Notice</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">About</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="mdi:faq"></iconify-icon>
                        <span>Faqs</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="tabler:social"></iconify-icon>
                        <span>Social Links</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="material-symbols:contact-page"></iconify-icon>
                        <span>Contact Details</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
