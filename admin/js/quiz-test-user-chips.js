// Insert new bp-lms-quiz-chip
$("#bp-lms-quiz-chip-input").keyup(function(event) {
    var data = this.value;
    if (event.keyCode === 13) {
        //alert(data);
        $( '<div class="bp-lms-quiz-chip"> '+data+' <span class="closebtn" >&times;</span></div>' ).insertBefore(this);
        $(this).val(null);
    }
});

// Remove bp-lms-quiz-chip
$(document).on('click','.closebtn',function() {
    $(this).parent().remove();
});