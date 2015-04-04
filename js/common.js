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