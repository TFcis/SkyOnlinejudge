$(document).ready(function(){
    var server_time = new Date($('#timer').html());
    var client_time = new Date();
    var offset = server_time.getTime() - client_time.getTime();

    function getTimeNow(offset){
        return new Date(client.getTime()+offset-client.getTimezoneOffset()*60000);
    }
    function getTimestampNow(offset){
        client = new Date();
        return getTimeNow(offset).toISOString().slice(0, 19).replace('T', ' ');
    }

    function UpdateTime(offset){
        timestamp = getTimestampNow(offset);
        $('#timer').html(timestamp);
        setTimeout(function(){
            UpdateTime(offset);
        },1000);
    }

    //clockdown
    function UpdateClockDown(offset){
        var req_update = false;
        $('[data-toggle="sky-countdown"]').each(function(){
            var end_time = new Date($(this).attr('data-value'));
            var client_time = getTimeNow(offset);
            var delta_sec = parseInt( (end_time - client_time - client.getTimezoneOffset()*60000 )/1000 );
            if( delta_sec < 0 ) delta_sec = 0;
            var html='';
            [[86400,'days '],[3600,':'],[60,':'],[0,'']].forEach(function(e){
                var arg = delta_sec;
                if( e[0]!=0 ){
                    arg = parseInt(delta_sec/e[0]);
                    delta_sec %= e[0];
                }
                if( e[0]!=86400 && arg<=9 ){
                    arg = '0'+arg;
                }
                if( !(e[0]==86400&&arg==0) ){//ignore 0days
                    html = html+arg+e[1];
                }
            });
            $(this).html(html);
            if( delta_sec!=0 ){
                req_update = true;
            }else{
                if( typeof $(this).attr('onclockdownzero') === 'string' ){
                    var cmd = $(this).attr('onclockdownzero');
                    setTimeout(function(){
                        eval(cmd);
                    },1);
                }
            }
        });
        if( req_update!=0 ){
            setTimeout(function(){
                UpdateClockDown(offset);
            },1000);
        }
    }
    UpdateTime(offset);
    UpdateClockDown(offset);
});
