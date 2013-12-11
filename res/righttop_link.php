<?php 
if (!isset($navi_width)){
    $navi_width = "100%";
    
}

?>
<div align="center">
	<table width="<?php echo $navi_width;?>" border="0">
		<tr>
			<td width="30%">
				<div align="left">
					<a href="<?php echo $back; ?>"><?php echo $back_name; ?></a>
				</div>
			</td>
			<td width="40%">
				<div align="center">
					<h3>欢迎您<?php echo $_SESSION['role'].$_SESSION['account']; ?>!</h3>
				</div>
			</td >
			<td width="30%">
				<div align="right">
					<a href="<?php echo $link; ?>"><?php echo $link_name; ?></a>
				</div>
			</td>
		</tr>
	</table>
</div>