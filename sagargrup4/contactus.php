<?php
/*
Template Name: Contact Us
*/
get_header();
?>

<div class="container mx-auto mt-8 mb-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 pb-4">Contact Us</h1>
        <div class="flex flex-wrap -mx-4">
            <div class="w-full md:w-1/2 px-4 mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Get in Touch</h2>
                <p class="text-gray-600 mb-4">We'd love to hear from you! Whether you have a question about our apps, feedback, or just want to say hello, please feel free to reach out to us.</p>
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
                        <textarea id="message" name="message" rows="5" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Send Message</button>
                    </div>
                </form>
            </div>
            <div class="w-full md:w-1/2 px-4">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Our Location</h2>
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.323452938816!2d85.3239603150663!3d27.7029539827923!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19a7b5f0d7e5%3A0x64a7f06b0a8f0e6!2sKathmandu%2C%20Nepal!5e0!3m2!1sen!2snp!4v1627386883658!5m2!1sen!2snp" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
