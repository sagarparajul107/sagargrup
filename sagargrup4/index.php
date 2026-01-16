<?php get_header(); ?>

    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden py-20 bg-gradient-to-br from-blue-600 via-green-500 to-blue-800">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-white mb-6 text-shadow">Welcome to <span class="text-yellow-300"><?php bloginfo('name'); ?></span></h1>
                <p class="text-2xl text-gray-200 mb-8 max-w-3xl mx-auto"><?php bloginfo('description'); ?></p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#apps" class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-full font-bold text-lg hover-scale shadow-xl">
                        <i class="fas fa-rocket mr-2"></i>Explore Our Apps
                    </a>
                    <a href="#contact" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-full font-bold text-lg hover-scale">
                        <i class="fas fa-phone mr-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Apps Section -->
    <section id="apps" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold gradient-text mb-4">Our Mobile Applications</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover our innovative mobile apps available on Google Play Store and Amazon Appstore</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $args = array(
                    'post_type' => 'app',
                    'posts_per_page' => 3,
                );
                $apps_query = new WP_Query( $args );

                if ( $apps_query->have_posts() ) :
                    while ( $apps_query->have_posts() ) : $apps_query->the_post();
                ?>
                    <div class="card-hover bg-gradient-to-br from-blue-50 to-green-50 rounded-2xl p-8 shadow-xl">
                        <div class="text-center mb-6">
                            <div class="bg-blue-500 rounded-full p-4 w-20 h-20 mx-auto mb-4">
                                <?php if(has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'rounded-full')); ?>
                                <?php else: ?>
                                    <i class="fas fa-mobile-alt text-white text-3xl"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="text-gray-600 mb-6">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-3">
                            <a href="<?php echo get_post_meta(get_the_ID(), '_app_playstore_link', true); ?>" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full text-center font-semibold hover-scale">
                                <i class="fab fa-google-play mr-2"></i>Download on Play Store
                            </a>
                            <a href="<?php the_permalink(); ?>" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-full text-center font-semibold hover-scale">
                                <i class="fas fa-download mr-2"></i>Download APK
                            </a>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-center col-span-3">No apps found.</p>';
                endif;
                ?>
            </div>
            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('app'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-bold text-lg hover-scale shadow-xl">
                    <i class="fas fa-th-large mr-2"></i>View All Apps
                </a>
            </div>
        </div>
    </section>

    <!-- Company Blog Section -->
    <section id="blog" class="py-20 bg-gradient-to-br from-gray-100 to-blue-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold gradient-text mb-4">Technology Insights Blog</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Stay updated with the latest technology trends, insights, and company news</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                );
                $blog_query = new WP_Query( $args );

                if ( $blog_query->have_posts() ) :
                    while ( $blog_query->have_posts() ) : $blog_query->the_post();
                ?>
                <article class="card-hover bg-white rounded-2xl shadow-xl overflow-hidden">
                    <a href="<?php the_permalink(); ?>">
                        <?php if(has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('large', array('class' => 'h-48 w-full object-cover')); ?>
                        <?php else: ?>
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-48 flex items-center justify-center">
                                <i class="fas fa-code text-white text-6xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="p-6">
                        <div class="text-sm text-blue-600 font-semibold mb-2"><?php echo get_the_category_list(', '); ?> • <?php echo get_the_date(); ?></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="text-gray-600 mb-4"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">Read More <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-center col-span-3">No posts found.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Customer Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold gradient-text mb-4">Customer Services & Support</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We're here to help you with exceptional customer support and comprehensive services</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Service 1 -->
                <div class="text-center card-hover bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 shadow-lg">
                    <div class="bg-blue-500 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <i class="fas fa-headset text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-600 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Round-the-clock customer support for all your technical needs</p>
                </div>

                <!-- Service 2 -->
                <div class="text-center card-hover bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 shadow-lg">
                    <div class="bg-green-500 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <i class="fas fa-tools text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-green-600 mb-3">Technical Support</h3>
                    <p class="text-gray-600">Expert technical assistance and troubleshooting services</p>
                </div>

                <!-- Service 3 -->
                <div class="text-center card-hover bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 shadow-lg">
                    <div class="bg-purple-500 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <i class="fas fa-graduation-cap text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-600 mb-3">Training & Tutorials</h3>
                    <p class="text-gray-600">Comprehensive training materials and video tutorials</p>
                </div>

                <!-- Service 4 -->
                <div class="text-center card-hover bg-gradient-to-br from-orange-50 to-white rounded-2xl p-8 shadow-lg">
                    <div class="bg-orange-500 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <i class="fas fa-rocket text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-orange-600 mb-3">Updates & Upgrades</h3>
                    <p class="text-gray-600">Regular app updates and feature enhancements</p>
                </div>
            </div>

            <!-- Contact Options -->
            <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-3xl p-8 text-white">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <i class="fas fa-phone text-4xl mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Phone Support</h4>
                        <p class="text-blue-100">9741860714</p>
                        <p class="text-sm text-blue-200">Available 24/7</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-envelope text-4xl mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Email Support</h4>
                        <p class="text-blue-100">support@sagargrup.xyz</p>
                        <p class="text-sm text-blue-200">Response within 2 hours</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-comments text-4xl mb-4"></i>
                        <h4 class="text-xl font-bold mb-2">Live Chat</h4>
                        <p class="text-blue-100">Instant messaging support</p>
                        <p class="text-sm text-blue-200">Available during business hours</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-green-50">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-5xl font-bold gradient-text mb-6">About SagarGrup</h2>
                    <p class="text-xl text-gray-600 mb-6">We are a leading technology company specializing in innovative mobile app development, digital solutions, and cutting-edge software products.</p>
                    <p class="text-lg text-gray-600 mb-8">Our mission is to create technology that empowers businesses and individuals to achieve their goals through intuitive, powerful, and secure digital solutions.</p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">50+</div>
                            <div class="text-gray-600">Apps Developed</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">100K+</div>
                            <div class="text-gray-600">Happy Users</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">5+</div>
                            <div class="text-gray-600">Years Experience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600">24/7</div>
                            <div class="text-gray-600">Support Available</div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sagar.png" alt="SagarGrup Team" class="mx-auto h-64 w-64 rounded-full shadow-2xl hover-scale">
                    <div class="mt-8 bg-white rounded-2xl p-6 shadow-xl">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Our Core Values</h3>
                        <div class="flex justify-around">
                            <div class="text-center">
                                <i class="fas fa-lightbulb text-yellow-500 text-2xl mb-2"></i>
                                <div class="text-sm font-semibold">Innovation</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-shield-alt text-blue-500 text-2xl mb-2"></i>
                                <div class="text-sm font-semibold">Security</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-users text-green-500 text-2xl mb-2"></i>
                                <div class="text-sm font-semibold">Community</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-star text-purple-500 text-2xl mb-2"></i>
                                <div class="text-sm font-semibold">Excellence</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold gradient-text mb-4"><?php echo esc_html(get_option('sagargrup_get_in_touch_title', 'Get In Touch')); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?php echo esc_html(get_option('sagargrup_get_in_touch_description', 'Ready to start your next project? Contact us today and let\'s create something amazing together!')); ?></p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="card-hover bg-gradient-to-br from-blue-50 to-green-50 rounded-2xl p-8 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Send us a Message</h3>
                    <?php sagargrup_display_contact_status(); ?>
                    <?php 
                        $enable_attachments = get_option('sagargrup_enable_email_attachments', 0);
                        $enctype = $enable_attachments ? 'enctype="multipart/form-data"' : '';
                        ?>
                    <form method="POST" class="space-y-6" <?php echo $enctype; ?>>
                        <?php wp_nonce_field('sagargrup_contact_form_action', 'sagargrup_contact_nonce'); ?>
                        <input type="hidden" name="sagargrup_contact_form" value="1">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">First Name</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Last Name</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                            <input type="text" name="subject" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Message</label>
                            <textarea name="message" rows="5" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none"></textarea>
                        </div>
                        <?php 
                        $enable_attachments = get_option('sagargrup_enable_email_attachments', 0);
                        if ($enable_attachments): 
                        ?>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Attachment (optional)</label>
                            <input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:outline-none">
                            <p class="text-sm text-gray-500 mt-1">Allowed formats: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT (Max 5MB)</p>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-4 rounded-lg font-bold text-lg hover-scale shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="card-hover bg-white rounded-2xl p-6 shadow-xl border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="bg-blue-500 rounded-full p-3 mr-4">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Our Office</h4>
                                <p class="text-gray-600">kathmandu kalankai nepal</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-hover bg-white rounded-2xl p-6 shadow-xl border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-3 mr-4">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Phone Number</h4>
                                <p class="text-gray-600">9741860714</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-hover bg-white rounded-2xl p-6 shadow-xl border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="bg-purple-500 rounded-full p-3 mr-4">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Email Address</h4>
                                <p class="text-gray-600">support@sagargrup.xyz</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-hover bg-white rounded-2xl p-6 shadow-xl border-l-4 border-orange-500">
                        <div class="flex items-center">
                            <div class="bg-orange-500 rounded-full p-3 mr-4">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Business Hours</h4>
                                <p class="text-gray-600">Mon - Fri: 9:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl p-6 text-white text-center">
                        <h4 class="text-xl font-bold mb-4">Follow Us</h4>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="bg-white bg-opacity-20 rounded-full p-3 hover:bg-opacity-30 transition duration-300">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 rounded-full p-3 hover:bg-opacity-30 transition duration-300">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 rounded-full p-3 hover:bg-opacity-30 transition duration-300">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 rounded-full p-3 hover:bg-opacity-30 transition duration-300">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
