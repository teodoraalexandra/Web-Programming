<?php
	require 'config.php';

	$grand_total = 0;
	$allItems = '';
	$items = array();

	$sql = "SELECT CONCAT(product_name, '(', qty, ')') AS ItemQty, total_price FROM cart";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

	while($row = $result->fetch_assoc()){

		$grand_total += $row['total_price'];

		$items[] = $row['ItemQty'];
	}

	$allItems = implode(", ", $items);
?>

<!DOCTYPE html>
<html lang = "en">

<head>
	<meta charset="UTF-8">
	<meta name="author" content="Laborator 7">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale = 1, shrink-to-fit = no">
	<title>Checkout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>

</head>

<body>
	<nav class="navbar navbar-expand-md bg-dark navbar-dark">
	  <!-- Brand -->
	  <a class="navbar-brand" href="index.php">Tattoo mania</a>

	  <!-- Toggler/collapsibe Button -->
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <!-- Navbar links -->
	  <div class="collapse navbar-collapse" id="collapsibleNavbar">
	    <ul class="navbar-nav ml-auto">
	      <li class="nav-item">
	        <a class="nav-link active" href="index.php">Products</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="#">Categories</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="checkout.php">Checkout</a>
	      </li> 
	      <li class="nav-item">
	        <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id = "cart-item" class="badge badge-danger"></span></a>
	      </li> 
	    </ul>
	  </div> 
	</nav>

	<div class = "container">
		<div class="row justify-content-center">
			<div class="col-lg-6 px-4 pb-4" id="order">
				<h4 class="text-center text-info p-2">Complete your order</h4>

				<div class="jumbotron p-3 mb-2 text-center">
					<h6 class="lead"><b>Product(s) : </b><?= $allItems; ?></h6>
					<h6 class="lead"><b>Delivery charge : </b>Free</h6>
					<h5><b>Total : </b><?= number_format($grand_total,2) ?>&nbsp;lei</h5>
				</div>

				<form action="" method="post" id="placeOrder">
					<input type="hidden" name="products" value="<?= $allItems; ?>">
					<input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
					<div class="form-group">
						<input type="text" name="name" class="form-control" placeholder="Enter name" required>
					</div>

					<div class="form-group">
						<input type="email" name="email" class="form-control" placeholder="Enter e-mail" required>
					</div>

					<div class="form-group">
						<input type="tel" name="phone" class="form-control" placeholder="Enter phone" required>
					</div>

					<div class="form-group">
						<textarea name="address" class="form-control" rows="3" cols="10"placeholder="Enter delivery address here"></textarea>
					</div>

					<h6 class="text-center lead">Select payment mode</h6>
					<div class="form-group">
						<select name="pmode" class="form-control">
							<option value="" selected disabled>-Select payment mode-</option>
							<option value="cash_on_delivery">Cash on delivery</option>
							<option value="net_banking">Internet banking</option>
							<option value="card">Debit/Credit card</option>
						</select>
					</div>

					<div class="form-group">
						<input type="submit" name="submit" value="Place order" class="btn btn-danger btn-block">
					</div>

				</form>
			</div>
		</div>
		
	</div>


	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<!-- script for ajax call -->
	<script type="text/javascript">
		$(document).ready(function(){

			$("#placeOrder").submit(function(e){
				e.preventDefault();

				$.ajax({
					url: 'action.php',
					method: 'post',
					data: $('form').serialize()+'&action=order',
					success: function(response) {
						$('#order').html(response);
					}
				});
			});
				
			load_cart_item_number();

			//create a function to update continuously the number near cart
			function load_cart_item_number(){
        		$.ajax({
	          		url: 'action.php',
	          		method: 'get',
	          		data: {cartItem:"cart_item"},
	          		success:function(response){
	            		$("#cart-item").html(response);
	          		}
	        	}); 
      		}
		});
	</script>

</body>
</html>