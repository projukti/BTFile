<?php  include("connection.php");

$ua = $_SERVER["HTTP_USER_AGENT"];
$soc = strpos($ua, 'Safari') ? true : false;
$crm = strpos($ua, 'Chrome') ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BanglaTimeTV Video Search</title>

    <link rel="stylesheet" href="js/jquery-ui-1.11.4.custom/jquery-ui.css">
    <link rel="stylesheet" href="js/jquery-ui-1.11.4.custom/jquery-ui.theme.css">
    <style>
        body * {-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; font-family: monospace}
        body {background: white url(images/M6LRT8x.jpg) center top fixed; background-size: cover}
        .logo {margin: 30px auto; display: block}
        form {display: table; position: relative}
        form input[type=search] {width: 460px; margin-right: 5px; float: left; padding: 6px 5px; padding-left: 20px; outline: none; border: 1px solid rgb(13,90,190); /*-webkit-border-radius: 0 10px; -moz-border-radius: 0 10px; border-radius: 0 10px;*/}
        form input[type=search]:focus {border: 1px solid rgb(235,17,42)}
        form input[type=submit] {width: 100px; float: left; border: 2px solid rgb(255, 69, 89); background: rgb(235,17,42); color: #fff; padding: 5px 10px; /*-webkit-border-radius: 10px 0px; -moz-border-radius: 10px 0px; border-radius: 10px 0px;*/ outline: none}
        form input[type=submit]:active {border: 2px solid rgb(235,17,42)}
        span.searchGlass {position: absolute; display: block; top: 7px; left: 5px; width: 14px; height: 14px; background: url(images/search_mobile.png) center center no-repeat; background-size: contain;}
        .mediumPanel {display: block; margin: 30px auto; width: 565px;}
        .largePanel {display: block; margin: 30px auto; width: 600px; background: rgba(255,255,255,0.7); border: 1px solid rgb(13,90,190)}
        table {width: 100%; border-collapse: collapse;}
        table tr:first-child td {text-align: center; font-size: 16px; background: rgba(235,17,42,0.7); color: white}
        table tr td {border-bottom: 1px solid rgba(13,90,190,0.2)}
        small {font-size: 12px}
        video {width: 97%; margin-left: 10px; margin-top: 5px; margin-bottom: 5px}
    </style>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script>
        $(function () {
            var availableTags = [
                <?php 
			$sel_tag=mysql_query("select * FROM tags");
			while($fetch_tag=mysql_fetch_array($sel_tag)){
			?>
                "<?php echo $fetch_tag['tag'];?>",
				<?php }?>
            ];

            function split(val) {
                return val.split(/,\s*/);
            }

            function extractLast(term) {
                return split(term).pop();
            }

            $("#search")
                // don't navigate away from the field on tab when selecting an item
                    .bind("keydown", function (event) {
                        if (event.keyCode === $.ui.keyCode.TAB &&
                                $(this).autocomplete("instance").menu.active) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 0,
                        source: function (request, response) {
                            // delegate back to autocomplete, but extract the last term
                            response($.ui.autocomplete.filter(
                                    availableTags, extractLast(request.term)));
                        },
                        focus: function () {
                            // prevent value inserted on focus
                            return false;
                        },
                        select: function (event, ui) {
                            var terms = split(this.value);
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push(ui.item.value);
                            // add placeholder to get the comma-and-space at the end
                            terms.push("");
                            this.value = terms.join(", ");
                            return false;
                        }
                    });
        });
    </script>
</head>
<body>
<img src="images/logo.png" alt="" class="logo">
<div class="mediumPanel">
    <form action="" method="post">
    <input type="hidden" name="mode" value="search">
        <span class="searchGlass"></span>
        <input type="search" id="search" name="tags" value="<?php echo $_REQUEST['tags']; ?>">
        <input type="submit" value="Search">
    </form>
</div>
<?php
if(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='search')
			{
?>
<div class="largePanel">
    <table>
        <tr>
            <td colspan="2">
                Search Result<br>
                <small>Right click on filename and select 'save as'...</small>
            </td>
        </tr>
        <!--repeat-->
        
            <!--<td width="50%">
                <video controls preload="none">
                    <source src="movie.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </td>-->
            <?php 
			$tags = explode(",", $_REQUEST['tags']);
			$count=count($tags);	 
			$sel="select * from files where ";
			for($i=0;$i<=$count;$i++)
			{
			if($i==0){	
			if(!empty($tags[$i]))
			{	
			$sel.=" file_tags LIKE '%".trim($tags[$i])."%'";
			}
			}
			else
			{
			if(!empty($tags[$i]))
			{	
			$sel.=" and file_tags LIKE '%".trim($tags[$i])."%'";
			}	
			}
			}
			//echo $sel;
			$query=mysql_query($sel);
			while($fetch=mysql_fetch_array($query)){	
			?>
            <tr>
			<td width="50%">
			
            <?php if($soc==true AND $crm==false) { ?><video controls preload="none">
               <source src="<?php echo strtolower($fetch['drive']);?>/<?php echo $fetch['file_name'];?>" type="video/mp4">
                    Your browser does not support the video tag.
			</video> <?php } else {?>
			<embed type="application/x-vlc-plugin" pluginspage="http://www.videolan.org" src="<?php echo strtolower($fetch['drive']);?>/<?php echo $fetch['file_name'];?>" width="300" height="250" autoplay="false" autostart="0" />
			<?php } ?>
            </td>
            <td width="50%" style="text-align: center">
                <a href="<?php echo strtolower($fetch['drive']);?>/<?php echo $fetch['file_name'];?>"><?php echo $fetch['file_name'];?></a>
            </td>
            </tr>
            <?php }//}?>
        
    </table>
</div>
			<?php } ?>
</body>
</html>