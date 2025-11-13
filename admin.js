jQuery(document).ready(function ($) {
    $('#regenerate-key').click(function () {
        if (confirm(perfectContentConnector.confirmMessage)) {
            $.post(perfectContentConnector.ajaxurl, {
                action: 'perfect_content_connector_regenerate_key',
                nonce: perfectContentConnector.nonce
            }, function (response) {
                if (response.success) {
                    $('input[value="' + perfectContentConnector.currentApiKey + '"]').val(response.data.new_key);
                    alert(perfectContentConnector.successMessage);
                } else {
                    alert(perfectContentConnector.errorMessage);
                }
            });
        }
    });
});

