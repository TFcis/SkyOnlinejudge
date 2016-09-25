//comment example
//https://google-styleguide.googlecode.com/svn/trunk/javascriptguide.xml
/**
 * Adds two numbers
 * @param {Number} a 
 * @param {Number} b
 * @return {Number} sum
 */
 
/*
    Template Load functions
*/

/**
 * Set/Get SiteRoot URL
 * @param {String} url
 * @return {String} site url,or false when something error
 * siteroot() is once setting only!
 */
function siteroot(url){
    if( siteroot.url === undefined ){
        if( url === undefined || typeof(url) != 'string' ){
            console.log("set siteroot but fail");
            return false;
        }
        else{
            siteroot.url = url;
        }
    }
    return siteroot.url;
}

function api_submit(url,fmid,showid,succ,err)
{
    $.post(url,$(fmid).serialize(),function(res){
        if(res.status == 'error'){
            $(showid).html(res.data);
            $(showid).css('color','Red');
            if (typeof err != 'undefined')
                err(res);
        }else{
            $(showid).css('color','Lime');
            $(showid).html('Success!');
            if (typeof succ != 'undefined')
                succ(res);
        }
    },"json").fail(function(e){
        console.log(e);
        $(showid).html(e.responseText);
    });
}

//http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function getParameterByName(name) {
    url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

//http://stackoverflow.com/questions/5999118/add-or-update-query-string-parameter
function updateQueryStringParameter(key, value) {
    url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = url.indexOf('?') !== -1 ? "&" : "?";
    if (url.match(re)) {
        url =  url.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        url =  url + separator + key + "=" + value;
    }
    location.replace(url);
}
