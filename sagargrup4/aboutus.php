<?php
/*
Template Name: About Us
*/
get_header();
?>

<div class="container mx-auto mt-8 mb-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 pb-4">About Us</h1>
        <div class="prose max-w-full">
            <p>Welcome to <?php bloginfo('name'); ?>! We are a team of passionate developers and tech enthusiasts dedicated to bringing you the best and most innovative apps. Our mission is to create high-quality applications that are both useful and enjoyable.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">Our Story</h2>
            <p>Founded in 2025, <?php bloginfo('name'); ?> started with a simple idea: to make technology accessible and fun for everyone. We noticed a gap in the market for apps that were not only functional but also beautifully designed. Since then, we have been working tirelessly to build a portfolio of apps that meet this standard.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">Our Team</h2>
            <p>Our team is made up of talented individuals from diverse backgrounds. We have experts in mobile development, UI/UX design, and quality assurance. Together, we collaborate to create seamless and engaging user experiences.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">Our Vision</h2>
            <p>We believe that technology can be a powerful force for good. Our vision is to continue pushing the boundaries of what's possible in app development, creating tools that help people in their daily lives. We are committed to innovation, quality, and user satisfaction.</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
