<!-- Footer -->
    <footer class="animated-gradient text-white py-16">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sagar.png" alt="SagarGrup" class="h-10 w-10 rounded-full">
                        <h3 class="text-2xl font-bold"><?php bloginfo('name'); ?></h3>
                    </div>
                    <p class="text-gray-200 mb-4">Leading technology company creating innovative mobile solutions for the digital world.</p>
                    <div class="flex space-x-3">
                        <a href="#" class="bg-white bg-opacity-20 rounded-full p-2 hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-white bg-opacity-20 rounded-full p-2 hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-white bg-opacity-20 rounded-full p-2 hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="bg-white bg-opacity-20 rounded-full p-2 hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_quick_links',
                            'container' => false,
                            'items_wrap' => '<ul class="space-y-2">%3$s</ul>',
                        ) );
                    ?>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Our Services</h4>
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_services',
                            'container' => false,
                            'items_wrap' => '<ul class="space-y-2">%3$s</ul>',
                        ) );
                    ?>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Contact Info</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            <span class="text-gray-200">kathmandu kalankai nepal</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            <span class="text-gray-200">9741860714</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span class="text-gray-200">support@sagargrup.xyz</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-white border-opacity-20 pt-8 text-center">
                <p class="text-gray-200 mb-4">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved. | <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy Policy</a> | <a href="<?php echo esc_url( home_url( '/terms-of-service' ) ); ?>">Terms of Service</a></p>
                <p class="text-sm text-gray-300">Powered by Innovation • Built with Excellence • Secured with Trust</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
