<?php 
    $this->pageTitle = "";
?>
<style type="text/css">
.rectangle{padding:20px 30px;
    border: 1px solid white;
    border-radius: 10px;
    -webkit-box-shadow: 0 2px 6px rgba(0,0,0,0.55);
    -moz-box-shadow: 0 2px 6px rgba(0,0,0,0.55);
    box-shadow: 0 2px 6px rgba(0,0,0,0.55);
    background-color:#fff;
}

.code{color:#d71345;text-align:center;}
.thanks{color:#f47920;font-weight:normal;}
</style>
<body>

<body>
    <div data-role="content">
        <div class="rectangle">
        	<h1 class="thanks"><?php echo $title;?></h1>
        	<h3><?php echo $detail;?></h3>
        </div>
    </div>