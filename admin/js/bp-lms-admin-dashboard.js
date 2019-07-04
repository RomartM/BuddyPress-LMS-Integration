
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

setTimeout(function(){ // ASAP Alter data export
    try {
        var r = document.getElementsByClassName("lpie-export-source")[0];
        r.children[0].firstElementChild.lastChild.data = "SolidNet"
    } catch (err) {
        console.log("Not ment for this page.")
    }
}, 1000);