<?php
	require 'config.php';

	if(isset($_POST['gold'])){
		$filter = $_POST['gold'];
		sendAlert($filter);
	} 

	if(isset($_POST['minimalist'])){
		$filter = $_POST['minimalist'];
		sendAlert($filter);
	} 

	if(isset($_POST['black'])){
		$filter = $_POST['black'];
		sendAlert($filter);
	} 

	if(isset($_POST['white'])){
		$filter = $_POST['white'];
		sendAlert($filter);
	} 

	function sendAlert($filter) {
		require 'config.php';

		echo '<div id="message"></div>
		<div id="filter_message"></div>
		<div class = "row mt-2 pb-3">';
			
		//we will get the current page number
		if (isset($_GET['pagenofilter'])) {
		    $pagenofilter = $_GET['pagenofilter'];
		} else {
		    $pagenofilter = 1; 
		} 

		$no_of_records_per_page = 4;
		$offset = ($pagenofilter-1) * $no_of_records_per_page;

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
		while ($row = $result->fetch_assoc()):
			

		echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-2">
			<div class="card-deck">
				<div class="card p-2 border-secondary mb-2">
					<img src="'.$row['product_image'].'" class = "card-img-top" height="250">
						<div class="card-body p-1">
							<h4 class = "card-title text-center text-info">'.$row['product_name'].'</h4>
							<h5 class = "card-text text-center text-danger"> '.number_format($row['product_price'],2).' lei </h5>
						</div>

						<div class="card-footer p-1">
							<form action="" class="form-submit">
								<input type="hidden" class="pid" value=" '.$row['id'].' ">
								<input type="hidden" class="pname" value=" '.$row['product_name'].' ">
								<input type="hidden" class="pprice" value=" '.$row['product_price'].' ">
								<input type="hidden" class="pimage" value=" '.$row['product_image'].' ">
								<input type="hidden" class="pcode" value=" '.$row['product_code'].' ">
								<button class = "btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to cart</button>
							</form>
						</div>
					</div>
				</div>
			</div>';

			endwhile; 

			echo '<div class="pagination">
					<ul>
			        	<li style="color:black;"><a href="?pagenofilter=1&filter='.$filter.'">&laquo;&laquo; First</a></li>

			        	<li style="color:black;" class="';

			        	if($pagenofilter <= 1){ echo 'disabled'; } 

			        	echo '"> <a href="';

			            if($pagenofilter <= 1) { 
			            	echo '#'; 
			            } else { 
			            	echo "?pagenofilter=".($pagenofilter - 1); 
			            } 

			            echo '&filter='.$filter.' ">&laquo; Prev</a>
			        </li>

			        <li style="color:black;" class="';
			        
			        if($pagenofilter >= $total_pages){ echo 'disabled'; } 

			        echo '"> <a href="';

			        if($pagenofilter >= $total_pages){ 
			            echo '#'; 
			        } else { 
			            echo "?pagenofilter=".($pagenofilter + 1); 
			        } 

			        echo '&filter='.$filter.' ">Next &raquo;</a>
			        </li>

			        <li style="color:black;"><a href="?pagenofilter=';

			        echo $total_pages; 

			        echo '&filter='.$filter.' ">Last &raquo;&raquo;</a></li>
	            </ul>
        	</div>';

		echo '</div>';
	}

?>

<!-- script for ajax call -->
<script type="text/javascript">
	$(document).ready(function(){

		$(".addItemBtn").click(function(e){
			e.preventDefault();
			//get all the values from the form 
			var $form = $(this).closest(".form-submit");
			var pid = $form.find(".pid").val();
			var pname = $form.find(".pname").val();
			var pprice = Number($form.find(".pprice").val());
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
	});
</script>



