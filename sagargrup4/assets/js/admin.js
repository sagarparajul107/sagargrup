jQuery(document).ready(function($) {
    // Media uploader
    $(document).on('click', '.sagargrup-upload-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var inputField = button.prev('input[type="text"]');
        
        var uploader = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = uploader.state().get('selection').first().toJSON();
            inputField.val(attachment.url);
        }).open();
    });

    // Add screenshot
    $('#add_screenshot').on('click', function(e) {
        e.preventDefault();
        if ($('#screenshots_wrapper').children().length < 10) {
            $('#screenshots_wrapper').append('<div><input type="text" name="app_screenshots[]" class="widefat" value=""><button type="button" class="button sagargrup-upload-button">Upload Image</button> <a href="#" class="remove_screenshot">Remove</a></div>');
        } else {
            alert('You can upload a maximum of 10 screenshots.');
        }
    });

    // Remove screenshot
    $(document).on('click', '.remove_screenshot', function(e) {
        e.preventDefault();
        if ($('#screenshots_wrapper').children().length > 3) {
            $(this).parent().remove();
        } else {
            alert('You must have at least 3 screenshots.');
        }
    });
});
