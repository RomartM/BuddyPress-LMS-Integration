/* get query string parameters */
var urlParams = new URLSearchParams(window.location.search);

function change_registration_title(title) {
  jQuery("div.register-page>aside>p").text(title);
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
    change_registration_title('Sign-up as Student');
    select_user_role_value_as(1);
  } else if (user_role == 'parent') {
    change_registration_title('Sign-up as Parent');
    select_user_role_value_as(2);
  } else {
    /* faculty/staff */
    change_registration_title('Sign-up as Faculty/Staff');
    select_user_role_value_as(3);
  }
}

/* Run this script after loaded */
document.addEventListener("DOMContentLoaded", function(){
  /* check the query string */
  if (urlParams.has('action') && (urlParams.get('action') == 'register-user')) {
    if (urlParams.has('user')) {
      var user_role = urlParams.get('user');

      /* Verify user roles */
      if (user_role == 'student' || user_role == 'parent' || user_role == 'faculty') {
        setup_registration_by(user_role);
      } else {
        /* default: student */
        setup_registration_by('student');
      }
    } else {
      /* default: student */
      setup_registration_by('student');
    }
  } else {
    /* default: student */
    setup_registration_by('student');
  }
});
