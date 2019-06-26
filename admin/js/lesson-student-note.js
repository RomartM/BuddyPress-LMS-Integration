(function ($) {

    class MyNote {
        constructor(note_list_obj, lesson_id, course_id) {
            this.note_list_obj = note_list_obj || [];
            this.lesson_id = lesson_id;
            this.course_id = course_id;
        }

        add(title){
            var format = {
              id: this.__uuidv4(),
              title:title || 'Title Here',
              lesson_id: this.lesson_id,
              course_id: this.course_id,
              obj_content: {}
            };
            this.note_list_obj.push(format);
            return format.id;
        }

        edit(id){
            var data = this.note_list_obj.find(data=>data.id == id);
            if(data === undefined){
                return "0"
            }
            return data;
        }

        delete(id){
            var data = this.note_list_obj.filter(item=>item.id!==id);
            if(data === undefined){
                return "0"
            }
        }

        __uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
    }

    $(document).ready(function () {
        var note;

        if (typeof(Storage) !== "undefined") {
            if (localStorage.getItem('fab-color') === null) {
                localStorage.setItem("fab-color", "blue");
            }
            $('.fabs').addClass(localStorage.getItem("fab-color"));
        } else {
            $('.fabs').addClass("blue");
        }


        function init(){
            if(MyNoteObject===undefined){
                console.log("My Note Ajax not initiated");
                return 0;
            }
            note = new MyNote(
                MyNoteObject.note_data,
                MyNoteObject.lesson_id,
                MyNoteObject.course_id
            );
        }


        //Fab click
        $('#prime').click(function() {
            toggleFab();
        });

        $('.bp_lms_lesson_student_note_new_note_btn').toggle(function () {
            $('.bp_lms_lesson_student_note_new_note_btn > i').removeClass('zmdi-plus-circle');
            $('.bp_lms_lesson_student_note_new_note_btn > i').addClass('zmdi-close');
            $('.bp_lms_lesson_student_note_list_wrapper').fadeOut();
            $('.bp_lms_lesson_student_note_new_title').fadeIn();
        }, function () {
            $('.bp_lms_lesson_student_note_new_note_btn > i').removeClass('zmdi-close');
            $('.bp_lms_lesson_student_note_new_note_btn > i').addClass('zmdi-plus-circle');
            $('.bp_lms_lesson_student_note_list_wrapper').fadeIn();
            $('.bp_lms_lesson_student_note_new_title').fadeOut();
        });

//Speak admin msg
        function botSpeak(text) {
            if ('speechSynthesis' in window) {
                var msg = new SpeechSynthesisUtterance(text);
                window.speechSynthesis.speak(msg);
            }
        }

//Toggle bp_lms_lesson_student_note and links
        function toggleFab() {
            $('.prime').toggleClass('zmdi-plus');
            $('.prime').toggleClass('zmdi-close');
            $('.prime').toggleClass('is-active');
            $('#prime').toggleClass('is-float');
            $('.bp_lms_lesson_student_note').toggleClass('is-visible');
            $('.fab').toggleClass('is-visible');

        }

//User msg
        function userSend(text) {
            var img = '<i class="zmdi zmdi-account"></i>';
            $('#bp_lms_lesson_student_note_converse').append('<div class="bp_lms_lesson_student_note_msg_item bp_lms_lesson_student_note_msg_item_user"><div class="bp_lms_lesson_student_note_avatar">' + img + '</div>' + text + '</div>');
            $('#bp_lms_lesson_student_noteSend').val('');
            if ($('.bp_lms_lesson_student_note_converse').height() >= 256) {
                $('.bp_lms_lesson_student_note_converse').addClass('is-max');
            }
            $('.bp_lms_lesson_student_note_converse').scrollTop($('.bp_lms_lesson_student_note_converse')[0].scrollHeight);
        }

//Admin msg
        function adminSend(text) {
            $('#bp_lms_lesson_student_note_converse').append('<div class="bp_lms_lesson_student_note_msg_item bp_lms_lesson_student_note_msg_item_admin"><div class="bp_lms_lesson_student_note_avatar"><i class="zmdi zmdi-headset-mic"></i></div>' + text + '</div>');
            botSpeak(text);
            if ($('.bp_lms_lesson_student_note_converse').height() >= 256) {
                $('.bp_lms_lesson_student_note_converse').addClass('is-max');
            }
            $('.bp_lms_lesson_student_note_converse').scrollTop($('.bp_lms_lesson_student_note_converse')[0].scrollHeight);
        }

//Send input using enter and send key
        $('#bp_lms_lesson_student_noteSend').bind("enterChat", function(e) {
            userSend($('#bp_lms_lesson_student_noteSend').val());
            adminSend('How may I help you.');
        });
        $('#fab_send').bind("enterChat", function(e) {
            userSend($('#bp_lms_lesson_student_noteSend').val());
            adminSend('How may I help you.');
        });
        $('#bp_lms_lesson_student_noteSend').keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                if (jQuery.trim($('#bp_lms_lesson_student_noteSend').val()) !== '') {
                    $(this).trigger("enterChat");
                }
            }
        });

        $('#fab_send').click(function(e) {
            if (jQuery.trim($('#bp_lms_lesson_student_noteSend').val()) !== '') {
                $(this).trigger("enterChat");
            }
        });

//Listen user voice
        $('#fab_listen').click(function() {
            var recognition = new webkitSpeechRecognition();
            recognition.onresult = function(event) {
                userSend(event.results[0][0].transcript);
            }
            recognition.start();
        });

// Color options
        $(".bp_lms_lesson_student_note_color").click(function(e) {
            $('.fabs').removeClass(localStorage.getItem("fab-color"));
            $('.fabs').addClass($(this).attr('color'));
            localStorage.setItem("fab-color", $(this).attr('color'));
        });

        $('.bp_lms_lesson_student_note_option').click(function(e) {
            $(this).toggleClass('is-dropped');
        });

//Loader effect
        function loadBeat(beat) {
            beat ? $('.bp_lms_lesson_student_note_loader').addClass('is-loading') : $('.bp_lms_lesson_student_note_loader').removeClass('is-loading');
        }

// Ripple effect
        var target, ink, d, x, y;
        $(".fab").click(function(e) {
            target = $(this);
            //create .ink element if it doesn't exist
            if (target.find(".ink").length == 0)
                target.prepend("<span class='ink'></span>");

            ink = target.find(".ink");
            //incase of quick double clicks stop the previous animation
            ink.removeClass("animate");

            //set size of .ink
            if (!ink.height() && !ink.width()) {
                //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
                d = Math.max(target.outerWidth(), target.outerHeight());
                ink.css({
                    height: d,
                    width: d
                });
            }

            //get click coordinates
            //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
            x = e.pageX - target.offset().left - ink.width() / 2;
            y = e.pageY - target.offset().top - ink.height() / 2;

            //set the position and add class .animate
            ink.css({
                top: y + 'px',
                left: x + 'px'
            }).addClass("animate");
        });

//Cookies handler
        function createCookie(name, value, days) {
            var expires;

            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            } else {
                expires = "";
            }
            document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
        }

        function readCookie(name) {
            var nameEQ = encodeURIComponent(name) + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }

        function eraseCookie(name) {
            createCookie(name, "", -1);
        }

//User login
        function logUser() {
            hideChat(true);
            $('#bp_lms_lesson_student_note_send_email').click(function(e) {
                var email = $('#bp_lms_lesson_student_note_log_email').val();
                if (jQuery.trim(email) !== '' && validateEmail(email)) {
                    $('.bp_lms_lesson_student_note_login_alert').html('');
                    loadBeat(true);
                    createCookie('fab_bp_lms_lesson_student_note_email', email, 100);
                    if (checkEmail(email)) {
                        //email exist and get and set username in session
                        hideChat(false);
                    } else {
                        setTimeout(createUsername, 3000);
                    }
                } else {
                    $('.bp_lms_lesson_student_note_login_alert').html('Invalid email.');
                }
            });
        }

        function createUsername() {
            loadBeat(false);
            $('#bp_lms_lesson_student_note_log_email').val('');
            $('#bp_lms_lesson_student_note_send_email').children('i').removeClass('zmdi-email').addClass('zmdi-account');
            $('#bp_lms_lesson_student_note_log_email').attr('placeholder', 'Username');
            $('#bp_lms_lesson_student_note_send_email').attr('id', 'bp_lms_lesson_student_note_send_username');
            $('#bp_lms_lesson_student_note_log_email').attr('id', 'bp_lms_lesson_student_note_log_username');
            $('#bp_lms_lesson_student_note_send_username').click(function(e) {
                var username = $('#bp_lms_lesson_student_note_log_username').val();
                if (jQuery.trim(username) !== '') {
                    loadBeat(true);
                    if (checkUsername(username)) {
                        //username is taken
                        $('.bp_lms_lesson_student_note_login_alert').html('Username is taken.');
                    } else {
                        //save username in DB and session
                        createCookie('fab_bp_lms_lesson_student_note_username', username, 100);
                        hideChat(false);
                    }
                } else {
                    $('.bp_lms_lesson_student_note_login_alert').html('Please provide username.');
                }
            });
        }

        function hideChat(hide) {
            if (hide) {
                $('.bp_lms_lesson_student_note_converse').css('display', 'none');
                $('.fab_field').css('display', 'none');
            } else {
                $('#bp_lms_lesson_student_note_head').html(readCookie('fab_bp_lms_lesson_student_note_username'));
                // Help
                $('#fab_help').click(function(){userSend('Help!');});
                $('.bp_lms_lesson_student_note_list_wrapper').css('display', 'none');
                $('.bp_lms_lesson_student_note_converse').css('display', 'block');
                $('.fab_field').css('display', 'inline-block');
            }
        }

        function checkEmail(email) {
            //check if email exist in DB
            return false;
        }

        function checkUsername(username) {
            //check if username exist in DB
            return false;
        }

        function validateEmail(email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (!emailReg.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        if (readCookie('fab_bp_lms_lesson_student_note_username') === null || readCookie('fab_bp_lms_lesson_student_note_email') === null) {
            logUser();
        } else {
            hideChat(false);
        }
        init();
    });
}(jQuery));