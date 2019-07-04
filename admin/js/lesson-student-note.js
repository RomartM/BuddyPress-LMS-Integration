(function ($) {

    $(document).ready(function () {


        function __uuidv4() {
            return 'xxxxxxxx-xxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        $('.bp_lms_lesson_student_note').draggable();
        $('.bp_lms_lesson_student_note').resizable({
            minWidth: 200,
            minHeight: 400,
            handles: "n, e, s, w, ne, se, sw, nw"
        });

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
        }

        // Close Note
        $('.bp_lms_lesson_student_note_close_btn').click(function () {
            $('#prime').show();
            toggleFab();
        });

        //Open Note
        $('#prime').click(function() {
            // Get Note Sheets
            ajaxLoadList();
            $(this).hide();
            toggleFab();
        });

        // New Note Sheet
        $('.bp_lms_lesson_student_note_new_note_btn').click(function () {
            var id = __uuidv4();
            var ed = tinyMCE.get('student_note');
            $(this).hide();
            $('.bp_lms_lesson_student_note_close_note_btn').show();
            $('.bp_lms_lesson_student_note_save_btn').attr('note-id', id);
            $('.bp_lms_lesson_student_note_list_wrapper').fadeOut();
            $('.bp_lms_lesson_student_note_new_title').fadeIn();
            // Show Save and Delete Button
            $('.bp_lms_lesson_student_note_save_btn').show().attr('note-id', id);
            $('.bp_lms_lesson_student_note_delete_btn').show().attr('note-id', id);
            // Initiate Editor
            var template_format = `<h3>${$('h3.course-item-title')[0].innerText}</h3>
                                   ${$('.bp_lms_lesson_code')[0].innerHTML}<hr><p>Note Here</p>`
            ed.setContent(template_format);
        });

        // Close Note Sheet
        $('.bp_lms_lesson_student_note_close_note_btn').click(function () {
            $(this).hide();
            $('.bp_lms_lesson_student_note_new_note_btn').show();
            $('.bp_lms_lesson_student_note_list_wrapper').fadeIn();
            $('.bp_lms_lesson_student_note_new_title').fadeOut();
            // Hide Save and Delete Button
            $('.bp_lms_lesson_student_note_save_btn').hide()
            $('.bp_lms_lesson_student_note_delete_btn').hide()
            // Refresh Note Sheet List
            ajaxLoadList();
        });

        // Save Note
        $('.bp_lms_lesson_student_note_save_btn').click(function () {
            ajaxSave($(this).attr('note-id'), tinyMCE.get('student_note').getContent());
        });

        // Delete Sheet
        $('.bp_lms_lesson_student_note_delete_btn').click(function () {
            // Start network request
            requestHandler('delete', {
                lesson_id: $(this).attr("lesson-id"),
                course_id: $(this).attr("course-id"),
                nid: $(this).attr("note-id")
            }).then(function (response) {
                if(response==="success"){
                    alert("Deleted");
                    $('.bp_lms_lesson_student_note_close_note_btn').click();
                }else{
                    alert("Failed to delete");
                }
            });
        });

        // Toggle fullscreen mode
        $('.bp_lms_lesson_student_note_fullscreen_btn').toggle(function () {
            $(this).attr('title', 'Minimize Window')
            $('.bp_lms_lesson_student_note').draggable('disable');
            $('.bp_lms_lesson_student_note').resizable('disable');
            localStorage.setItem('StudentNoteWindowSettings',
                JSON.stringify($('.bp_lms_lesson_student_note').css(['width', 'height', 'left', 'top'])))
            $('.bp_lms_lesson_student_note').css({
                'left':'0',
                'top':'0',
                'width': '100%',
                'height': '100%'
            });
            $('.bp_lms_lesson_student_note_fullscreen_btn > i').removeClass('zmdi-window-maximize');
            $('.bp_lms_lesson_student_note_fullscreen_btn > i').addClass('zmdi-window-minimize');
        }, function () {
            $(this).attr('title', 'Maximize Window')
            $('.bp_lms_lesson_student_note').draggable('enable');
            $('.bp_lms_lesson_student_note').resizable('enable');
            var window_data = localStorage.getItem('StudentNoteWindowSettings');
            if(window_data != null){
                $('.bp_lms_lesson_student_note').css(JSON.parse(window_data));
            }
            $('.bp_lms_lesson_student_note_fullscreen_btn > i').removeClass('zmdi-window-minimize');
            $('.bp_lms_lesson_student_note_fullscreen_btn > i').addClass('zmdi-window-maximize');
        });

        $("a").click(function (eve) {
            eve.preventDefault()
            ajaxSave($('.bp_lms_lesson_student_note_save_btn').attr('note-id'), tinyMCE.get('student_note').getContent(), function () {
                console.log("Reloaded")
                //window.location.href = eve.target.href;
            });
        });

        // Load sheet list
        function ajaxLoadList() {
            requestHandler('list', {
                course_id: MyNoteObject.course_id
            }).then(function (response) {
                $('.bp_lms_lesson_student_note_list_wrapper').empty();
                var raw_data = JSON.parse(response);
                var title, data_chunk, temp;
                for (var key in raw_data) {
                    if (raw_data.hasOwnProperty(key)) {
                        temp = JSON.parse(raw_data[key]);
                        data_chunk = key.split(":");
                        title = temp !== null? temp.title || "Untitled" : "Untitled";
                        $('.bp_lms_lesson_student_note_list_wrapper').append(`<div class="bp_lms_lesson_note_item" data-id="${data_chunk[3]}" lesson-id="${data_chunk[1]}" course-id="${data_chunk[2]}">${title}</div>`);
                    }
                }
                $('.bp_lms_lesson_note_item').click(function () {
                    var id = $(this).attr("data-id");
                    $('.bp_lms_lesson_student_note_save_btn').attr({
                        'note-id': id,
                        'data-status': 'update'
                    });
                    $('.bp_lms_lesson_student_note_new_note_btn').hide();
                    $('.bp_lms_lesson_student_note_close_note_btn').show();
                    $('.bp_lms_lesson_student_note_save_btn').attr('note-id', id);
                    $('.bp_lms_lesson_student_note_list_wrapper').fadeOut();
                    $('.bp_lms_lesson_student_note_new_title').fadeIn();
                    // Show Save and Delete Button
                    $('.bp_lms_lesson_student_note_save_btn').show().attr('note-id', id);
                    $('.bp_lms_lesson_student_note_delete_btn').show().attr('note-id', id);
                    $('.bp_lms_lesson_student_note_list_wrapper').fadeOut();
                    $('.bp_lms_lesson_student_note_new_title').fadeIn();
                    ajaxLoad(id, $(this).attr("lesson-id"), $(this).attr("course-id"));
                });
            })
        }


        // Load individual sheet
        function ajaxLoad(nid, lid, cid) {
            var ed = tinyMCE.get('student_note');

            $('.bp_lms_lesson_student_note_delete_btn').attr({
                "note-id":nid,
                "lesson-id":lid,
                "course-id": cid
            });
            // Change data status
            $('.bp_lms_lesson_student_note_save_btn').attr('data-status', 'update');
            // Clear previous content
            $('#noteTitle').val("");
            ed.setContent("");

            ed.setProgressState(1);
            // Start network request
            requestHandler('retrieve', {
                lesson_id: lid,
                course_id: cid,
                nid: nid
            }).then(function (response) {
                var data = JSON.parse(response);
                $('#noteTitle').val(data.title || "Untitled");
                ed.setContent(data.obj);
                ed.setProgressState(0);
            });
        }

        function ajaxSave(nid, data, callback) {
            var ed = tinyMCE.get('student_note');
            var save_element = $('.bp_lms_lesson_student_note_save_btn');

            ed.setProgressState(1);
            requestHandler(save_element.attr('data-status') || "save", {
                lesson_id: MyNoteObject.lesson_id,
                course_id: MyNoteObject.course_id,
                nid: nid,
                title: $('#noteTitle').val() || "Untitled",
                data_obj: data
            }).then(function (response) {
                ed.setProgressState(0); // Hide progress
                if(response==="success"){
                    save_element.attr('data-status', 'update');
                }
                if(callback){
                    callback();
                }
            });
        }

        function requestHandler(typ, obj) {

            var data = {
                'action': 'bp_lms_student_note_ajax',
                'type': typ || null,
                'obj': obj
            };

            return new Promise(function (resolve, reject) {
                $.post(MyNoteObject.ajaxurl, data, function(response) {
                    resolve(response)
                }).fail(function(error) {
                    reject(error)
                });
            });
        }

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


//Listen user voice
        $('#fab_listen').click(function() {
            var recognition = new webkitSpeechRecognition();
            recognition.onresult = function(event) {
                userSend(event.results[0][0].transcript);
            }
            recognition.start();
        });
        init();
    });
}(jQuery));