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
    },"json").error(function(e){
        console.log(e);
    });
}