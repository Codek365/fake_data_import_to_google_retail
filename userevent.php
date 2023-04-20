<?php 
require_once "errors_show.php";
if (isset($_GET['eventType'])) {

	if (isset($_GET['start_date'])) {
		$start_date = strtotime($_GET['start_date']);
	}

	if (isset($_GET['end_date'])) {
		$end_date = strtotime($_GET['end_date']);
	}

	if (isset($_GET['eventRepeat'])) {
		$eventRepeat = $_GET['eventRepeat'];
	}

	if (isset($_GET['pjID'])) {
		$pjID = $_GET['pjID'];

	}

	$eventType  = $_GET['eventType'];
	$start = $start_date;
	$end = $end_date;

	$dir = "tmp/";
	$file = $dir . $pjID . "_" . generateRandomString() . "_" . $eventType . "_" . date("Y-m-d", $start) . "_" . date("Y-m-d", $end) . "_Repeat" . $eventRepeat ."_" . "data.json";

	generateJson($eventType, $start, $end, $eventRepeat, $file, $pjID);
}


function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


function generateVisiterID()
{
	$visiterID = ["user_01","user_02","user_03","user_04","user_05","user_06","user_07","user_08","user_09"];

	return $visiterID[array_rand($visiterID)];
}

function generateCategories($pjID)
{
	if ($pjID == 'pj-1') {
		$categories = ["Jacket","Shirt","women","Default Category","Pant"];
	} else {
		$categories = ["Apparel","Accessories","Office","Default Category"];
	}
	

	return $categories[array_rand($categories)];
}

function generateProducts($pjID)
{
	if ($pjID == 'pj-1') {
		$products = [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
	} else {
		$products = ["GGCOAAPR155410","GGCOGADC100813","GGCOGADC100814","GGCOGADC100815","GGCOGADC100816","GGCOGADC100817","GGCOGAED156912","GGCOGAEJ153715","GGCOGAEJ153717","GGCOGAEJ153718","GGCOGALB153913","GGCOGALC100713","GGCOGAXT154229","GGCOGAYC154115","GGCOGCBA161199","GGCOGCBA164499","GGCOGOAC101259","GGOEAAEC172013","GGOEAAEC172017","GGOEAAEH129616","GGOEAAEH129617","GGOEAAEL130813","GGOEAAEL130815","GGOEAAEL130818","GGOEAAKQ137410","GGOEAAWL130145","GGOEAAWL130147","GGOEAAXL129928","GGOEAAXQ129830","GGOEAAYH130212","GGOEAAYL130313","GGOEAAYL130315","GGOEACBA116699","GGOEAFBA115599","GGOEAFDH105799","GGOEAFKQ130599","GGOEAHPL130910","GGOECAEB163513","GGOECAEB163614","GGOECAEB165413","GGOECAEB165414","GGOECAEB165513","GGOECOLJ164299","GGOEGAAB118913","GGOEGAAB118914","GGOEGAAB118916","GGOEGAAH134316","GGOEGAAH136915","GGOEGAAQ117715","GGOEGAAQ117716","GGOEGAAR134513","GGOEGABB099199","GGOEGACH161516","GGOEGACH161517","GGOEGACH161518"];
	}
	
	


	return $item = 
	[
	  	"product" => [
	  		"id" => (string) $products[array_rand($products)]
	  	],
	  	"quantity" => 3,
	];
}

function generateJson($eventType, $start, $end, $eventRepeat, $file, $pjID = null) {
	$timezone = new \DateTimeZone('UTC');
	$loop = 60*24*60/$eventRepeat-1; // 60s * $loop_you want *  60m * 24h
	$r = null;
	$j= 0;
	for ($i=$start; $i<=$end; $i+=$loop) {
		$date = date("Y-m-d H:m:s",  $i);
		$needed_time = new \DateTime($date, $timezone);
	  	$arr["eventType"] = $eventType;
	  	$arr["visitorId"] = generateVisiterID();
	  	$arr["eventTime"] = $needed_time->format(\DATE_RFC3339);

	  	if ($eventType == 'home-page-view') {
	  		$arr["productDetails"] = generateProducts($pjID);
	  	}
	  	if ($eventType == 'detail-page-view') {
	  		$arr["productDetails"] = generateProducts($pjID);
	  	}

	  	if ($eventType == 'category-page-view') {
	  		$arr["pageCategories"] = generateCategories($pjID);
	  	}

	  	if ($eventType == 'add-to-cart') {
	  		$arr["productDetails"] = generateProducts($pjID);
	  		$arr['cartId'] = 'mobile';
	  	}
	  	
	  	if ($eventType == 'search') {
	  		$arr["productDetails"] = generateProducts($pjID);
	  		$arr["pageCategories"] = generateCategories($pjID);
	  	}
	  	
	  	$r .= json_encode($arr) .PHP_EOL;
	  	file_put_contents( $file,$r);

	}

}


function download($file_name)
{
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Content-Length: ' . filesize($file_name));
	header('Pragma: public');

	flush();
	readfile($file_name,true);
	die();
}

?>

<?php
if(isset($_GET['link']))
{
    $var_1 = $_GET['link'];
    $file = $var_1;

if (file_exists($file))
    {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
    }
} //- the missing closing brace



?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
		<!-- datepicker styles -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
		<!-- font-awesome -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<title>Hello, world!</title>
		<style type="text/css">
			.boxsizingBorder {
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
}
		</style>
	</head>
	<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-md-5">
				<form  method="GET">
					<div class="form-inline">
						<div class="input-group mb-2 mr-sm-2">
							<label>Project ID:</label>
							<select class="custom-select" name="pjID" id="pjID">
								<option value="yagi" 
								<?= isset($_GET['pjID']) && $_GET['pjID'] =="pj-1"? "selected" : "" ?>
								>pj-1</option>
								<option value="tf-learn" 
								<?= isset($_GET['pjID']) && $_GET['pjID'] =="pj-2" ? "selected" : "" ?>
								>pj-2</option>
							</select>
						</div>
						<div class="input-group mb-2 mr-sm-2" >
							<label>Event Type:</label>
							<select class="custom-select" name="eventType" id="eventType">
								<option value="home-page-view" 
									<?= isset($_GET['eventType']) && $_GET['eventType'] =="home-page-view" ? "selected" : "" ?>
								>home-page-view</option>
								<option value="detail-page-view"
									<?= isset($_GET['eventType']) && $_GET['eventType'] =="detail-page-view" ? "selected" : "" ?>
								>detail-page-view</option>
								<option value="category-page-view"
									<?= isset($_GET['eventType']) && $_GET['eventType'] =="category-page-view" ? "selected" : "" ?>
								>category-page-view</option>
								<option value="add-to-cart"
									<?= isset($_GET['eventType']) && $_GET['eventType'] =="add-to-cart" ? "selected" : "" ?>
								>add-to-cart</option>
								<option value="search"
									<?= isset($_GET['eventType']) && $_GET['eventType'] =="search" ? "selected" : "" ?>
								>search</option>
							</select>
							<label class="sr-only" for="inlineFormInputName2">Name</label>
						</div>
						<div class="input-group mb-2 mr-sm-2">
							<label>Start Date:</label>
							<div class="start_datepicker date input-group">
								<input name="start_date" type="text" placeholder="Start Date" class="form-control" id="fecha1" autocomplete="off" required value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : "" ;?>">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-1 mr-sm-1">
							<label>End Date:</label>
							<div class="end_datepicker date input-group">
								<input name="end_date" type="text" placeholder="End Date" class="form-control" id="fecha1" autocomplete="off" required value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : "" ;?>">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-2 mr-sm-2" >
							<label>Repeat:</label>
							<select class="custom-select" name="eventRepeat" id="eventRepeat">
								<option value="10" <?= isset($_GET['eventRepeat']) && $_GET['eventRepeat'] ==10 ? "selected" : "" ?>>10</option>
								<option value="100" <?= isset($_GET['eventRepeat']) && $_GET['eventRepeat'] ==100 ? "selected" : "" ?>>100</option>
							</select>
							<label class="sr-only" for="inlineFormInputName2">Name</label>
						</div>
						
						<button type="submit" class="btn btn-lg btn-primary mb-2 mr-5">Submit</button>
						<?php
							// download($file_name);
							// if (file_exists($file) && $eventType) {
							// 	echo '<a class="btn btn-lg mb-2 btn-success" href="download.php?link=' . $file . '">Download here</a>';
							// }
						?>
					</div>
					<div class="row">
						<div class="col-md-3 text-center">
							
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
			  	<table class="table table-bordered table-inverse table-hover">
			  		<thead>
			  			<tr>
			  				<th>File</th>
			  				<th>Action</th>
			  			</tr>
			  		</thead>
					<tbody>
						<?php
						$dir = 'tmp';
						$files = scandir($dir);
						rsort($files);
						foreach ($files as $line) {
						if ($line != '.' && $line != '..') {
							$line_file = "download.php?link=tmp/" . $line;
						?>
						<tr>
							<td><?=$line?></td>
							<td><a target="blank" class="btn btn-lg mb-2 btn-success" href="<?=$line_file?>">Download here</a></td>
						</tr>
						<?php }}?>
						
					</tbody>
			  	</table>
			</div>
		</div>	
	</div>
		<!-- Optional JavaScript; choose one of the two! -->
		<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
		
		<!-- Datepicker -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
		<script type="text/javascript">
			$(function () {
				var currentDate = new Date();

				$('.start_datepicker').datepicker({
					language: "en",
					autoclose: true,
					format: "yyyy/mm/dd",
      				endDate: "currentDate",
      				maxDate: currentDate

				});
				$('.end_datepicker').datepicker({
					language: "en",
					autoclose: true,
					format: "yyyy/mm/dd",
					endDate: "currentDate",
      				maxDate: currentDate

				});
				$( "#eventType option:selected" ).text();
				$( "#eventRepeat option:selected" ).text();
				$( "#pjID option:selected" ).text();

			});
		</script>
	</body>
</html>

