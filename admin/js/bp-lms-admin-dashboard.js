function copyTestCodeToClipBoard() {
    /* Get the text field */
    var testCodeField = document.getElementById("bp_lms_test_code_field");

    /* Select the text field */
    testCodeField.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");
}

function generateTestCode() {
    var testCodeField = document.getElementById("bp_lms_test_code_field");
    testCodeField.value = String(uuidv4()).slice(0, 8);
}

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

// Tamper data on Export
setTimeout(function(){
    try {
        var r = document.getElementsByClassName("lpie-export-source")[0];
        r.children[0].firstElementChild.lastChild.data = "SolidNet"
    } catch (err) {
        console.log("Not ment for this page.")
    }
}, 1000);

// Hide If Menu Item on Appearance
setTimeout(function(){
    var list_a = document.getElementById("menu-appearance").lastChild.children;
    var count = list_a.length;
    var loc_adr = location.origin;
    for(var i=0; i<count; i++){
        if(list_a[i].firstChild.innerText=="If Menu"){
            list_a[i].firstChild.style.display = "none";
        }
    }
}, 500);


// Hide UpdraftPlus Backups Item on Appearance
setTimeout(function(){
    var list_a = document.getElementById("menu-settings").lastChild.children;
    var count = list_a.length;
    var loc_adr = location.origin;
    for(var i=0; i<count; i++){
        if(list_a[i].firstChild.innerText=="UpdraftPlus Backups"){
            list_a[i].firstChild.style.display = "none";
        }
    }
}, 500);