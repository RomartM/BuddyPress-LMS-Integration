jQuery(function (jq) {

    function hexToString(hex) {
        var string = '';
        for (var i = 0; i < hex.length; i += 2) {
            string += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
        }
        return string;
    }

    /* get query string parameters */
    var urlParams = new URLSearchParams(window.location.search);
    if(Boolean(urlParams.get('redirect')) && Boolean(urlParams.get('response'))){
        var message_content = hexToString(urlParams.get('m-content'));
        switch (urlParams.get('response')){
            case "404":
                if(jq(".entry-content").length!==0){
                    jq(".entry-content").prepend('<div style="margin: 10px 2px;background-color: #FFCCBC;padding: 10px;border-left-style: solid;border-left-color: #FF5722;"><span>'+message_content+'<span></span></span></div>');
                }else{
                    alert(message_content);
                }
                break;
        }
    }
});