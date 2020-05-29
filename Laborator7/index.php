<!DOCTYPE html>
<html lang = "en">
<!-- In this lab you will have to develop a server-side web application in PHP. The web application has to manipulate a Mysql database with 1 to 3 tables and should implement the following base operations on these tables: select, insert, delete, update. Also the web application must use AJAX for getting data asynchronously from the web server and the web application should contain at least 5 web pages (client-side html or server-side php). -->

<!-- Write a web application for an e-commerce store. The application should maintain information about the products it sells in the database. The user should browse products by categories (use AJAX for this), add and remove products to a shopping cart. Product browsing should be paged - products are displayed on pages with maximum 4 products on a page (you should be able to go to the previous and the next page). -->

<head>
	<meta charset="UTF-8">
	<meta name="author" content="Laborator 7">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale = 1, shrink-to-fit = no">
	<title>E-commerce</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>

	<style>
		.pagination {
        	text-align: center;
        	margin-top: 35%;
        	margin-left: 35%;
        	position: absolute;
	    }

	    .pagination ul {
	    	text-align: center;
	        display: inline-block;
	        margin: 0; 
	        padding: 0;
	        zoom:1;
	        *display: inline;
	        list-style-type:none;
	    }

	    .pagination a {
			text-decoration: none;
			color: black;
	    }

	    .pagination a:hover {
	    	color: #5bc0de;
	    }

	    .pagination li {
	        float: left;
	        padding: 2px 5px;
	        text-decoration: none;
	    }
	</style>

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

	      <!-- Dropdown -->
    		<li class="nav-item dropdown">
      			<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        			Categories
      			</a>

		      	<div class="dropdown-menu">
					<a class="dropdown-item gold" href="#">Gold</a>
					<a class="dropdown-item minimalist" href="#">Minimalist</a>
					<a class="dropdown-item black" href="#">Black</a>
					<a class="dropdown-item white" href="#">White</a>
		     	</div>
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

	<div class = "container" id = "container">
		<div id="message"></div>
		<div id="filter_message"></div>
		<div class = "row mt-2 pb-3">
			<?php 
				include 'config.php';
				$result;

		        //we will get the current page number
		        if (isset($_GET['pageno'])) {
		            $pageno = $_GET['pageno'];
		            
		            $no_of_records_per_page = 4;
			        $offset = ($pageno-1) * $no_of_records_per_page;

			     	//Get the total number of pages
			    	$stmt = $conn->prepare("SELECT * FROM product");

					$stmt->execute(); 
					$stmt->store_result();
					$rows = $stmt->num_rows;

				    $total_pages = ceil($rows / $no_of_records_per_page);
			    	
					$stmt = $conn->prepare("SELECT * FROM product LIMIT $offset, $no_of_records_per_page");
					
					$stmt->execute();
					$result = $stmt->get_result();

				} else if (isset($_GET['pagenofilter'])) {
					$pageno = $_GET['pagenofilter'];

					$filter = $_GET['filter'];

					$no_of_records_per_page = 4;
			        $offset = ($pageno-1) * $no_of_records_per_page;

			        //Get the total number of pages
			    	$stmt = $conn->prepare("SELECT * FROM product WHERE product_code=?");
			    	$stmt->bind_param("s", $filter);

			    	$stmt->execute(); 
					$stmt->store_result();
					$rows = $stmt->num_rows;

				    $total_pages = ceil($rows / $no_of_records_per_page);

				    $stmt = $conn->prepare("SELECT * FROM product WHERE product_code=? LIMIT $offset, $no_of_records_per_page");
					$stmt->bind_param("s",$filter);

				    $stmt->execute();
					$result = $stmt->get_result();


				} else {
		            $pageno = 1; 
		            
		            $no_of_records_per_page = 4;
			        $offset = ($pageno-1) * $no_of_records_per_page;

			     	//Get the total number of pages
			    	$stmt = $conn->prepare("SELECT * FROM product");

					$stmt->execute(); 
					$stmt->store_result();
					$rows = $stmt->num_rows;

				    $total_pages = ceil($rows / $no_of_records_per_page);
			    	
					$stmt = $conn->prepare("SELECT * FROM product LIMIT $offset, $no_of_records_per_page");
					
					$stmt->execute();
					$result = $stmt->get_result();
		        } 

				while ($row = $result->fetch_assoc()):
			?>

			<div class="col-sm-6 col-md-4 col-lg-3 mb-2">
				<div class="card-deck">
					<div class="card p-2 border-secondary mb-2">
						<img src="<?= $row['product_image'] ?>" class = "card-img-top" height="250">
						<div class="card-body p-1">
							<h4 class = "card-title text-center text-info">
								<?= $row['product_name'] ?>
							</h4>
							<h5 class = "card-text text-center text-danger"><?= number_format($row['product_price'],2) ?> lei </h5>
						</div>

						<!-- Add to cart button-->
						<div class="card-footer p-1">
							<form action="" class="form-submit">
								<input type="hidden" class="pid" value="<?= $row['id'] ?>">
								<input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
								<input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
								<input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
								<input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
								<button class = "btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to cart</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<?php endwhile; ?>

			<div class="pagination">
				<ul>
					<?php
					$page;

					if (isset($_GET['pagenofilter'])) {
						$pagenofilter = $_GET['pagenofilter'];

						//FIRST FILTER
						echo '<li style="color:black;"><a href="?pagenofilter=1&filter='.$filter.'">&laquo;&laquo; First</a></li>';

						//PREV FILTER
						echo '<li style="color:black;" class="';

			        	if($pagenofilter <= 1){ echo 'disabled'; } 

			        	echo '"> <a href="';

			            if($pagenofilter <= 1) { 
			            	echo '#'; 
			            } else { 
			            	echo "?pagenofilter=".($pagenofilter - 1); 
			            } 

			            echo '&filter='.$filter.' ">&laquo; Prev</a></li>';

			            //NEXT FILTER
			            echo '<li style="color:black;" class="';
			        
			        	if($pagenofilter >= $total_pages){ echo 'disabled'; } 

			        	echo '"> <a href="';

			        	if($pagenofilter >= $total_pages){ 
			            	echo '#'; 
			        	} else { 
			            	echo "?pagenofilter=".($pagenofilter + 1); 
			        	} 

			        	echo '&filter='.$filter.' ">Next &raquo;</a></li>';

						//LAST FILTER
						echo '<li style="color:black;"><a href="?pagenofilter='.$total_pages.'&filter='.$filter.' ">Last &raquo;&raquo;</a></li>';
					}

					else {
						//FIRST BUTTON
						echo '<li style="color:black;"><a href="?pageno=1">&laquo;&laquo; First</a></li>';

						//PREV BUTTON
						echo '<li style="color:black;" class="';

			        	if($pageno <= 1){ echo 'disabled'; } 

			        	echo '"> <a href="';

			            if($pageno <= 1) { 
			            	echo '#'; 
			            } else { 
			            	echo "?pageno=".($pageno - 1); 
			            } 

			            echo '">&laquo; Prev</a></li>';

						//NEXT BUTTON
						echo '<li style="color:black;" class="';
			        
			        	if($pageno >= $total_pages){ echo 'disabled'; } 

			        	echo '"> <a href="';

			        	if($pageno >= $total_pages){ 
			            	echo '#'; 
			        	} else { 
			            	echo "?pageno=".($pageno + 1); 
			        	} 

			        	echo '">Next &raquo;</a></li>';

						//LAST BUTTON
						echo '<li style="color:black;"><a href="?pageno='.$total_pages.'">Last &raquo;&raquo;</a></li>';
					}

					?>
	            </ul>
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

			$(".addItemBtn").click(function(e){
				e.preventDefault();
				//get all the values from the form 
				var $form = $(this).closest(".form-submit");
				var pid = $form.find(".pid").val();
				var pname = $form.find(".pname").val();
				var pprice = $form.find(".pprice").val();
				var pimage = $form.find(".pimage").val();
				var pcode = $form.find(".pcode").val();

				$.ajax({
					url: 'action.php', 
					method: 'post',
					data: {pid:pid, pname:pname, pprice:pprice, pimage:pimage, pcode:pcode},
					success: function(response){
						$("#message").html(response);
						window.scrollTo(0,0);
						load_cart_item_number();
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

      		//filter using ajax - gold
      		$(".gold").click(function(e){
      			var gold = "gold";

				$.ajax({
				  	url: 'filter.php',
				  	method:'post',
				  	data: { gold:gold },
			
				  	success: function(response){
						$("#container").html(response);
					}
				});
			});

			//filter using ajax - minimalist
      		$(".minimalist").click(function(e){
      			var minimalist = "minimalist";

				$.ajax({
				  	url: 'filter.php',
				  	method:'post',
				  	data: { minimalist:minimalist },
			
				  	success: function(response){
						$("#container").html(response);
					}
				});
			});

			//filter using ajax - black
      		$(".black").click(function(e){
      			var black = "black";

				$.ajax({
				  	url: 'filter.php',
				  	method:'post',
				  	data: { black:black },
			
				  	success: function(response){
						$("#container").html(response);
					}
				});
			});

			//filter using ajax - white
      		$(".white").click(function(e){
      			var white = "white";

				$.ajax({
				  	url: 'filter.php',
				  	method:'post',
				  	data: { white:white },
			
				  	success: function(response){
						$("#container").html(response);
					}
				});
			});

		});
	</script>

</body>
</html>