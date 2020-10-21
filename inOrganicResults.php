<?php //Single Top Answers Provider

     //Deploy calculator        
    if($toShow){
        if($is_mobile){
            echo "<div class = 'resultContainer'>
            <iframe src='http://jamunresultai.rf.gd/eval.php?evaluate=$calcCheck' frameborder='0' scrolling='auto' height='85px' 
            margin='0px'></iframe></div>" ;
        }
        else{
            echo "<div class = 'resultContainer'>
            <iframe src='http://jamunresultai.rf.gd/eval.php?evaluate=$calcCheck' frameborder='0' scrolling='auto' height='50px'
            margin='0px'></iframe></div>" ;
        }
    }            
    //Show Time Client
    else if($isShowTime){ echo("
        <script type='text/javascript'> 
        function display_c(){
        var refresh=1000; // Refresh rate in milli seconds
        mytime=setTimeout('display_ct()',refresh)
        }

        function display_ct() {
        var x = new Date()
        document.getElementById('ct').innerHTML = x;
        display_c();
        }
        </script>
        <body onload=display_ct();>
        <div class='resultContainert'>
        <p  id='ct' class='timeshow' ></p></div>");
    }
    //User Told Hello!
    else if($userHello){
        echo("
        <div class='resultContainert'>
        <p class='timeshow'> Hello User! Try Searching for <q>Calculator</q> or <q>What time is it?</q> or many more!<br>
        Happy Searching! with Jamun Search</p></div>");
    }

?>