<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50'); ?>>
    <!-- Header -->
    <header class="animated-gradient shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white text-shadow"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
                </div>
                <nav id="site-navigation" class="hidden md:flex items-center">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'items_wrap'     => '<ul class="flex space-x-8">%3$s</ul>',
                            'walker'         => new SagarGrup_Nav_Walker(),
                        ) );
                    ?>
                </nav>
                <button id="mobile-menu-button" class="md:hidden text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <div id="mobile-menu" class="hidden md:hidden">
                 <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'items_wrap' => '<ul class="flex flex-col mt-4 space-y-2">%3$s</ul>',
                    ) );
                ?>
            </div>
        </div>
    </header>
