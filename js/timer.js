$(document).ready(function(){
    var server_time = new Date($('#timer').html());
    var client_time = new Date();
    var offset = server_time.getTime() - client_time.getTime();
    function UpdateTime(offset){
        client = new Date();
        timestamp = new Date(client.getTime()+offset-client.getTimezoneOffset()*60000).toISOString().slice(0, 19).replace('T', ' ');
        $('#timer').html(timestamp);
        setTimeout(function(){
            UpdateTime(offset);
        },1000);
    }
    UpdateTime(offset);
});
