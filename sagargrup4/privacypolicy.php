<?php
/*
Template Name: Privacy Policy
*/
get_header();
?>

<div class="container mx-auto mt-8 mb-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 pb-4">Privacy Policy</h1>
        <div class="prose max-w-full">
            <p>Your privacy is important to us. It is <?php bloginfo('name'); ?>'s policy to respect your privacy regarding any information we may collect from you across our website, <a href="<?php echo home_url(); ?>"><?php echo home_url(); ?></a>, and other sites we own and operate.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">1. Information we collect</h2>
            <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">2. How we use your information</h2>
            <p>We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorized access, disclosure, copying, use or modification.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">3. Cookies</h2>
            <p>We use cookies to make our website easier for you to use. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">4. Links to other sites</h2>
            <p>Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective privacy policies.</p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-6">5. Changes to this policy</h2>
            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p>

            <p class="mt-6">This policy is effective as of 27 July 2025.</p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
