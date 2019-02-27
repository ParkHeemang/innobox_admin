<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>청년내일로</title>
	<script type="text/javascript" src="./Example/jquery-1.12.3.min.js"></script>
</head>

<style>
    body{ width: 100%;}
    .con_wrap {width: 100%;}
    .con_wrap img {width: 100%;}
    .ssHide {display: none;}    
</style>
<body>

    <div class="con_wrap">
        <img src="./img/index01.jpg" alt="" class="index01">
        <img src="./img/index02.jpg" alt="" class="index02 ssHide">
        <img src="./img/index04.jpg" alt="" class="index03 ssHide">
        <img src="./img/index03.jpg" alt="" class="index04 ssHide">
        <img src="./img/sub.jpg" alt="" class="sub ssHide">
    </div>
	
	<script>
        $('.index01').on('click',function(){
            var $this = $(this);
            $this.addClass('ssHide');
            $('.index02').removeClass('ssHide');
        })
        
        $('.index02').on('click',function(){
            var $this = $(this);
            $this.addClass('ssHide');
            $('.index03').removeClass('ssHide');
        })
        
        $('.index03').on('click',function(){
            var $this = $(this);
            $this.addClass('ssHide');
            $('.index04').removeClass('ssHide');
        })
        
        $('.index04').on('click',function(){
            var $this = $(this);
            $this.addClass('ssHide');
            $('.sub').removeClass('ssHide');
        })
        
        $('.sub').on('click',function(){
            var $this = $(this);
            $this.addClass('ssHide');
            $('.index01').removeClass('ssHide');
        })
    </script>
	
</body>
</html>