
    $(document).ready(function() {
        $('.export-to-sheet').click(function(event) {
            event.preventDefault(); // Prevent default navigation
            console.log('clicked');

            // Show confirmation dialog
            if (!confirm('Hantar semua data ke Google Sheet?')) {
                return false;
            }

            // Disable the link and show loading state
            let $link = $(this);
            $link.addClass('disabled').find('.link-text').text('Exporting...');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            $.ajax({
                url: $link.attr('href'), // Use the href from the link
                method: 'POST',
                data: {
                    // _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#message').text(response.message).show();
                    $('#error').hide();
                },
                error: function(xhr) {
                    $('#error').text('Error: ' + xhr.responseJSON.error).show();
                    $('#message').hide();
                },
                complete: function() {
                    // Re-enable the link
                    $link.removeClass('disabled').find('.link-text').text('Export');
                }
            });

            return false; // Prevent default behavior
        });
    });
