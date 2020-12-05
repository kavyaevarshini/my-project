
<?php
session_start();
$conn=mysqli_connect("localhost", "root", "","shopping_cart");

if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shop_cart"]))
	{
		$item_array_id=array_column($_SESSION["shop_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count=count($_SESSION["shop_cart"]);
			$item_array= array(
			'item_id'=>$_GET["id"],
			'item_name'=>$_POST["hidden_prod_name"],
			'item_price'=>$_POST["hidden_price"],
			'item_quantity'=>$_POST["quantity"]);
			$_SESSION["shop_cart"][$count]=$item_array;
		}
		else{
			echo '<script>alert("Item already added")</script>';
			echo '<script>window.location="index.php"</script>';

		}
	}
	else
	{
		$item_array= array(
			'item_id'=>$_GET["id"],
			'item_name'=>$_POST["hidden_prod_name"],
			'item_price'=>$_POST["hidden_price"],
			'item_quantity'=>$_POST["quantity"]);
					$_SESSION["shop_cart"][0]=$item_array;
	}

}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="delete")
	{
		foreach ($_SESSION["shop_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])  
			{
				unset($_SESSION["shop_cart"][$keys]);
				echo '<script>alert("item removed")</script>';
				echo '<script>window.location:index.php</script>';
			}
		}

	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title> simple shopping cart</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
	<br/>
	<div class="container" style="width:700px;">
	<h3 allign="center">shopping cart</h3>
	<?php
	$query="SELECT * FROM products ORDER BY id ASC";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0)
	{
		while($row=mysqli_fetch_array($result))
		{
	?>
<div class="col-md-4">
<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
<div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">  

<img src="<?php echo $row["image"];?>" class="img-responsive"/><br/>
<h4 class="text-info"><?php echo $row["prod_name"];?></h4>
<h4 class="text-danger">$<?php echo $row["price"];?></h4>
<input type="text" name="quantity" class="form-control" value="1"/>
<input type="hidden" name="hidden_prod_name" value="<?php echo $row["prod_name"];?>">
<input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>">
<input type="submit" name="add_to_cart" style="margin-top: 5px;" class="btn btn-success" value="add to cart"/>
</div>
</form>
</div>
			<?php	
		}
	}

?>
<div style="clear:both;"></div>
<br/>
<h3>Order Details</h3>
<div class="table-reponsive">
	<table class="table table-bordered">
		<tr>
			<th width="40%">Item name</th>
			<th width="10%">Quantity</th>
			<th width="20%">Price</th>
			<th width="15%">Total</th>
			<th width="5%">Action</th>
		</tr>
		<?php
		if(!empty($_SESSION["shop_cart"]))
		{
			$total=0;
			foreach ($_SESSION["shop_cart"] as $keys => $values) 
			{

			?>
			<tr>
				<td><?php echo $values["item_name"];?></td>
			<td><?php echo $values["item_quantity"]; ?></td>
			<td>$<?php echo $values["item_price"];?></td>
			<td><?php echo number_format($values["item_quantity"]*$values["item_price"],2);?></td>
<td><a href="index.php?action=delete&id=<?php echo $values["item_id"];?>"><span class="text-danger">Remove</span></a> </td>
			</tr>
				<?php
						$total = $total + ($values["item_quantity"] * $values["item_price"]);  

			}
			?>

			<tr>
				<td colspan="3" align="right">Total</td>
				<td align="right">$<?php echo number_format($total,2);?></td>
				<td></td> 
			</tr>
			<?php

		}
		?>
</table>
</div>

</div>
<br/>
</body>
</html>