<?php
/*
Template Name: Disclaimer
*/
get_header();
?>

<div class="container mx-auto mt-8 mb-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 pb-4">Disclaimer</h1>
        <div class="prose max-w-full">
            <p>The information provided by <?php bloginfo('name'); ?> on <a href="<?php echo home_url(); ?>"><?php echo home_url(); ?></a> is for general informational purposes only. All information on the Site is provided in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability or completeness of any information on the Site.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">External Links Disclaimer</h2>
            <p>The Site may contain (or you may be sent through the Site) links to other websites or content belonging to or originating from third parties or links to websites and features in banners or other advertising. Such external links are not investigated, monitored, or checked for accuracy, adequacy, validity, reliability, availability or completeness by us.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">Errors and Omissions Disclaimer</h2>
            <p>While we have made every attempt to ensure that the information contained in this site has been obtained from reliable sources, <?php bloginfo('name'); ?> is not responsible for any errors or omissions, or for the results obtained from the use of this information.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">Fair Use Disclaimer</h2>
            <p>The information on this website is for educational and informational purposes only. The content is not intended to be a substitute for professional advice. We have made every effort to ensure the accuracy of the information, but we cannot guarantee that it is free of errors.</p>

            <p class="mt-6">This disclaimer was last updated on 27 July 2025.</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
