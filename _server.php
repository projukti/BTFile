<?php  include("connection.php");
			
				
if(isset($_REQUEST['mode']) && $_REQUEST['mode']=='fileadd'){
	
	$chk_filename=mysql_num_rows(mysql_query("select * from files where `file_name`='".$_REQUEST['filename']."'"));	
	if($chk_filename==0){	
	$add_file= mysql_query("insert into `files` set
								`file_name`='".$_REQUEST['filename']."',
								`file_tags`='".$_REQUEST['tags']."'");
	$tags = explode(",", $_REQUEST['tags']);
	foreach($tags as $tag)
     {
	  $tag=trim($tag);	 
	$count_tags=mysql_num_rows(mysql_query("select * from tags where `tag`='$tag'"));
	 if($count_tags==0){
		 if(!empty($tag))
		 {	 	
	 	$add_tag= mysql_query("insert into `tags` set
								`tag`='".$tag."'");
		 }
	 }
	 }
	echo "<script type='text/javascript'>window.location='server.php?success';</script>";
	}
	else
	{
	echo "<script type='text/javascript'>window.location='server.php?err';</script>";	
	}
}
if(isset($_REQUEST['mode'])&& $_REQUEST['mode']=='fileedit'){
	
	$add_file= mysql_query("update `files` set `file_tags`='".$_REQUEST['tags']."' where id='".$_REQUEST['id']."'");
	
	$tags = explode(",", $_REQUEST['tags']);
	foreach($tags as $tag)
     {	
	 $tag=trim($tag);
	 $count_tags=mysql_num_rows(mysql_query("select * from tags where `tag`='$tag'"));
	 if($count_tags==0){
		 if(!empty($tag))
		 {
	 $add_tag= mysql_query("insert into `tags` set
								`tag`='".$tag."'");
		 }
	 }
	 }
	
	echo "<script type='text/javascript'>window.location='server.php?updated';</script>";
}
if(isset($_REQUEST['del'])){
	
	$add_file= mysql_query("delete from `files` where id='".$_REQUEST['id']."'");
	
	echo "<script type='text/javascript'>window.location='server.php?deleted';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BanglaTimeTV Video Panel</title>
    
    <link rel="stylesheet" href="js/jquery-ui-1.11.4.custom/jquery-ui.css">
    <link rel="stylesheet" href="js/jquery-ui-1.11.4.custom/jquery-ui.theme.css">
    <style>
        body * {-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; font-family: monospace}
        body {background: black url(images/fashionable-floral-backgrounds-wallpapers.jpg) center center; background-size: cover; background-attachment: fixed}
        .logo {margin: 170px auto 30px auto; display: block}
        .panel {display: block; background: rgba(255,255,255,0.7); width: 450px; padding: 15px; margin: 30px auto; border: 1px solid rgb(13,90,190)}
		.panel2 {display: block; background: rgba(255,255,255,0.7); width: 600px; padding: 15px; margin: 30px auto; border: 1px solid rgb(13,90,190)}
        form {display: table}
        form label {display: block; width: calc(50% - 8px); margin-right: 15px; float: left; clear: left}
        form input, form textarea {padding: 5px; width: calc(50% - 7px); margin-bottom: 15px; outline: none; border: 1px solid rgb(13,90,190)}
        form input:focus, form textarea:focus {border: 1px solid rgb(235,17,42)}
        form textarea {height: 80px; resize: none}
        form button {display: block; margin: 0 auto; border: 2px solid rgb(255, 69, 89); background: rgb(235,17,42); color: #fff; padding: 5px 10px; /*-webkit-border-radius: 10px 0px; -moz-border-radius: 10px 0px; border-radius: 10px 0px;*/ outline: none}
        form button:active {border: 2px solid rgb(235,17,42)}
		.suc{ text-align:center; color:#090; font-size:16px; font-weight:bold;}
		.err{ text-align:center; color:#F00; font-size:16px; font-weight:bold;}
    </style>
    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script>
        $(function () {
            var availableTags = 
			[
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

            $("#tags")
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
<div class="panel">
<?php if(isset($_REQUEST['success'])){echo '<span class="suc">Added Successfully</span>';}
if(isset($_REQUEST['updated'])){echo '<span class="suc">Updated Successfully</span>';}
if(isset($_REQUEST['err'])){echo '<span class="err">Sorry!!File Name Already Exists</span>';}
if(isset($_REQUEST['deleted'])){echo '<span class="err">Deleted Successfully</span>';}?>
    <form action="" name="filem" id="filem" method="post" enctype="application/x-www-form-urlencoded">
    	
        <?php if(isset($_REQUEST['id'])){ 
		$sel_query= mysql_fetch_array(mysql_query("select * from files where id='".$_REQUEST['id']."'"));
		?>
        <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
        <input type="hidden" name="mode" value="fileedit">
        <?php }else{?>
        <input type="hidden" name="mode" value="fileadd">
        <?php }?>
        <label for="filename">File Name</label>
        <input type="text" id="filename" name="filename" value="<?php if(isset($_REQUEST['id'])){ echo $sel_query['file_name'];}?>" <?php if(isset($_REQUEST['id'])){?> readonly<?php }?>>
        <label for="tags">Tags<br>[Separate each tag with a comma]</label>
        <input name="tags" id="tags" placeholder="tag1,tag2" value="<?php if(isset($_REQUEST['id'])){ echo $sel_query['file_tags'];}?>">
        <button type="submit" onclick="return confirmBeforeSubmit()">Submit</button>
    </form>
</div><br>

<div class="panel2">
    <form action="" name="filem" id="filem" method="post" enctype="application/x-www-form-urlencoded">
    	<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>File</td>
    <td>Tags</td>
    <td>Edit</td>
    <td>Delete</td>
  </tr>
  
  <?php $sel_query=mysql_query("select * from files");
		while($fetch=mysql_fetch_array($sel_query)){?>
  <tr>
    <td><?php echo $fetch['file_name'];?></td>
    <td><?php echo $fetch['file_tags'];?></td>
    <td><a href="server.php?id=<?php echo $fetch['id'];?>&edit">Edit</a></td>
    <td><a href="server.php?id=<?php echo $fetch['id'];?>&del">Delete</a></td>
  </tr>
  
  <?php }?>
</table>

    </form>
</div>
<script type="text/javascript">
    function confirmBeforeSubmit() {
        return confirm("Are you sure that you entered everything correctly?");
    }
</script>
</body>
</html>