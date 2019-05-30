function change_registration_title(title) {
    /* Default: CREATE AN ACCOUNT */
    var new_title = "CREATE AN ACCOUNT | " + title;

    /* Applicable on eduma template */
    jQuery("div.banner-wrapper>h1").text(new_title);
}

function select_user_role_value_as(id) {
    /*
      id = 1 -> student
      id = 2 -> parent
      id = 3 -> faculty
    */
    document.getElementById('field_7').selectedIndex = id;
}

/* Modify registration page */
function setup_registration_by(user_role) {
    if (user_role == 'student') {
        select_user_role_value_as(1);
    } else if (user_role == 'parent') {
        select_user_role_value_as(2);
    } else {
        /* faculty/staff */
        select_user_role_value_as(3);
    }
}

/* Run this script after loaded */
document.addEventListener("DOMContentLoaded", function() {
    /* get query string parameters */
    var urlParams = new URLSearchParams(window.location.search);
    var pathname = window.location.pathname;

    /* Restrict script to runs only on "/solidnet/registration/" link */
    if (pathname == "/solidnet/register/") {
        /* check the query string */
        if (urlParams.has('action') && (urlParams.get('action') == 'register-user')) {
            if (urlParams.has('user')) {
                var user_role = urlParams.get('user');

                /* Verify user roles */
                if (user_role == 'student' || user_role == 'parent' || user_role == 'faculty') {
                    setup_registration_by(user_role);
                    change_registration_title(user_role);
                } else {
                    /* default: student */
                    // setup_registration_by('student');
                    window.location.href = '/solidnet/pre-registration';
                }
            } else {
                /* default: student */
                // setup_registration_by('student');
                window.location.href = '/solidnet/pre-registration';
            }
        } else {
            /* default: student */
            // setup_registration_by('student');
            window.location.href = '/solidnet/pre-registration';
        }
    }
});
