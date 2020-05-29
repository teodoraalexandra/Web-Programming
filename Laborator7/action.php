<?php
	session_start();

	require 'config.php';

	if(isset($_POST['pid'])){
		//Super global variable
		$pid = $_POST['pid'];
		$pname = $_POST['pname'];
		$pprice = $_POST['pprice'];
		$pimage = $_POST['pimage'];
		$pcode = $_POST['pcode'];
		//By default, we store only 1 product in cart
		$pqty = 1;

		$stmt = $conn->prepare("SELECT product_image FROM cart WHERE product_image=?");
		$stmt->bind_param("s",$pimage);
		$stmt->execute();
		$res = $stmt->get_result();
		$r = $res->fetch_assoc();
		$code = isset($r['product_image']);

		if(!$code) {
			$query = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, qty, total_price, product_code) VALUES (?,?,?,?,?,?)");
			//qty is integer, that's why we use this pattern
			$query->bind_param("sssiss",$pname,$pprice,$pimage,$pqty,$pprice,$pcode);
			$query->execute();

			echo '<div class="alert alert-success alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Item added to your cart!</strong> 
				</div>';
		}
		else {
			echo '<div class="alert alert-danger alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Item already added to your cart!</strong> 
				</div>';
		}
	}

	if(isset($_GET["cartItem"]) && isset($_GET["cartItem"])){
		$stmt = $conn->prepare("SELECT * FROM cart"); 
		$stmt->execute(); 
		$stmt->store_result();
		$rows = $stmt->num_rows;

		echo $rows;
	}

	if(isset($_GET['remove'])){
		$id = $_GET['remove'];

		$stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();

		$_SESSION['showAlert'] = 'block';
		$_SESSION['message'] = 'Item removed from the cart!';

		header('location:cart.php');

	}

	if(isset($_GET['clear'])) {
		$stmt = $conn->prepare("DELETE FROM cart");
		$stmt->execute();

		$_SESSION['showAlert'] = 'block';
		$_SESSION['message'] = 'All items removed from the cart!';

		header('location:cart.php');
	}

	if(isset($_POST['qty'])){
		$qty = $_POST['qty'];
		$pid = $_POST['pid'];
		$pprice = $_POST['pprice'];

		$tprice = $qty*$pprice;

		$stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE id=?");
		$stmt->bind_param("isi", $qty, $tprice, $pid);

		$stmt->execute();
	}

	if(isset($_POST['action']) && isset($_POST['action']) == 'order') {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$products = $_POST['products'];
		$grand_total = $_POST['grand_total'];
		$address = $_POST['address'];
		$pmode = $_POST['pmode'];

		$data = '';

		$stmt = $conn->prepare("INSERT INTO orders (name, email, phone, address, pmode, products, amount_paid) VALUES (?,?,?,?,?,?,?)");
		$stmt->bind_param("sssssss",$name,$email,$phone,$address,$pmode,$products,$grand_total);
		$stmt->execute();
		$data .= '<div class="text-center">
				<h1 class="display-4 mt-2 text-danger">Thank you</h1>
				<h2 class="text-success">Your order was placed successfully.</h2>
				<h4 class="bg-danger text-light rounded p-2">Items purchased : '.$products.'</h4>
				<h4>Your name : '.$name.'</h4>
				<h4>Your email : '.$email.'</h4>
				<h4>Your phone : '.$phone.'</h4>
				<h4>Total amount paid : '.number_format($grand_total, 2).'</h4>
				<h4>Payment mode : '.$pmode.'</h4>
				</div>';

		//After the order is completed, we should delete items from cart 
		$stmt_delete = $conn->prepare("DELETE FROM cart");
		$stmt_delete->execute();

		echo $data;
	}
?>