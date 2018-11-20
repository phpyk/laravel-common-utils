<?php
namespace Phpyk\Utils;
/**
 * Created by PhpStorm.
 * User: phpyk
 * Date: 2018/11/20
 * Time: 10:06 AM
 */


class Browser
{
    public static function isWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    public static function isBrowser()
    {
        if(!isset($_SERVER['HTTP_USER_AGENT'])){
            return false;
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false )
            return true;
        if (strpos($_SERVER['HTTP_USER_AGENT'],'Mozilla') !== false )
            return true;
        if (strpos($_SERVER['HTTP_USER_AGENT'],'Chrome') !== false )
            return true;
        if (strpos($_SERVER['HTTP_USER_AGENT'],'UCBrowser') !== false )
            return true;
        if (strpos($_SERVER['HTTP_USER_AGENT'],'Safari') !== false )
            return true;
        return false;
    }

    public static function tipShow($msg, $redirect_url = null, $wait_time = 1, $target = 'window', $msg2 = '') {
        $html = "";
        if ($redirect_url != null) {
            $html.= '<p>' . $msg . '</p>';
            $html .= '<p class="afred">页面跳转中，请稍候 <span id="SeedTimeWaitSet">' . $wait_time . '</span> 秒，请点击<a href="' . $redirect_url . '">这里</a>直接跳转' . $msg2 . '</p>';
            $html .= "
	    	<script type=\"text/javascript\">
	    	function seed_redirect(){
	    	 	CloseSeedMsgBox();
	    	 	clearTimeout(redirectTimeOutId);
                    {$target}.location.href = '" . $redirect_url . "';
	    	}
	    	var seedTimeWait=" . $wait_time . ";
	    	var redirectTimeOutId = setTimeout('seed_redirect()', " . $wait_time . ");
            var stws = document.getElementById('SeedTimeWaitSet');
	    	setInterval(function (){
                try{
                    seedTimeWait = seedTimeWait-1;
                    stws.innerHTML = seedTimeWait;
                }catch(err)
                {
                }
	    	}, 1000);
	    	</script>";
        } else {
            $html.= '<p>' . $msg . '</p>';
        }

        die($html);
    }


    /**
     * 微店错误提示信息
     */
    public static function redirect($msg, $redirect_url = null, $wait_time = 5, $target = "window") {
        if ($wait_time >= 1000)
            $wait_time = floor($wait_time / 1000);
        $wait_second = $wait_time*1000;
        $html = <<<EOD
        <html>
        <head>
        <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, user-scalable=0, width=device-width"/>
        <meta name="format-detection" content="telephone=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://wd.ewanse.com/v2/js2014/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="http://wd.ewanse.com/v2/js2014/layer.m.js"></script>
<script type="text/javascript" src="http://wd.ewanse.com/v2/js2014/base.js"></script>
<title>跳转提示</title>
</head>

<body>
<script type="text/javascript">
msgBox('{$msg}',{$wait_time});
setTimeout("window.location.replace('{$redirect_url}')",{$wait_second});

</script>
</body>
</html>
EOD;
        die($html);
    }

}