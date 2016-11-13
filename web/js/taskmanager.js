$(function() {
    $(function(){
        //$('select').selectric();
    });

    var timeTracking = $("#time-tracking");
    var timeTrackingIcon = $("#time-tracking-icon");

    if(timeTracking.length != 0) {
        $.get(timeTracking.data('status'), function (data) {
            if (data.started == true) {

                var timeTrackingPath1 = timeTracking.data('uri');
                var timeTrackingPath2 = timeTracking.data('uri-reverse');

                timeTracking.data('uri', timeTrackingPath2);
                timeTracking.data('uri-reverse', timeTrackingPath1);

                timeTrackingIcon.html("stop");
            }

            timeTracking.css('visibility', 'visible');
        })
    }
});

$(function() {
    /**
     * Logic for our sweet time tracking feature
     */
    $("button#time-tracking").on('click', function() {
        var snackBarContainer = document.querySelector('#time-tracking-snackbar');

        var path = $(this).data('uri');

        var timeTracking = $("#time-tracking");
        var timeTrackingIcon = $("#time-tracking-icon");

        var timeTrackingPath1 = timeTracking.data('uri');
        var timeTrackingPath2 = timeTracking.data('uri-reverse');

        $.get(path, function (data) {

            if (data.success == true) {
                timeTracking.data('uri', timeTrackingPath2);
                timeTracking.data('uri-reverse', timeTrackingPath1);
                timeTrackingIcon.html(data.newIcon);

                snackBarContainer.MaterialSnackbar.showSnackbar({
                    message: data.message,
                    timeout: 1500
                });
            }

            console.log(data);
        });
    });

    /**
     * Allow user to click table row and redirect to correct path
     */
    $(".pointertr, button").on('click', function() {
        if ($(this).attr('id') == 'time-tracking') {
            return true;
        }

        var path = $(this).data('uri');

        if (path !== undefined) {
            window.location = path;
        }
    });

    $(".checkboxtr").on('click', function() {
        var checkbox = $(this).find('input');

        if (checkbox.prop('checked')) {
            checkbox.prop('checked', false);
        } else {
            checkbox.prop('checked', true);
        }
    });

    $("form > div > div").on('click', function() {
        $(this).find('input').select();
    });

    $("#link-to-grid").on('click', function() {
        window.history.back();
    });

    $(".btn-submit-form").on('click', function(e) {
        e.preventDefault();
        $(".form-card").find('form').submit();
    });
});
