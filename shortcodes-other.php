<?php

/* Redirect timer script on thank you page
----------------------------------------------- */
function k_redirect_timer_shortcode() { 

    return '
        <span class="small" id="timer"></span>
        <script type="text/javascript">
        var count = 5;
        var redirect = "index.php";
        function countDown(){
        var timer = document.getElementById("timer");
            if(count > 0){
                count--;
                timer.innerHTML = "Ця сторінка буде перенаправлена через <b>"+count+"</b> секунд.";
                setTimeout("countDown()", 1000);
            }else{
                window.history.go(-1);
            }
        }
        countDown();
        </script>   
    ';

}
add_shortcode( 'k_redirect_timer', 'k_redirect_timer_shortcode' );