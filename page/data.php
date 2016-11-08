<?php 
	
	require("../functions.php");
	
	require("../class/Car.class.php");
	$Car = new Car($mysqli);
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["plate"]) && 
		isset($_POST["plate"]) && 
		!empty($_POST["color"]) && 
		!empty($_POST["color"])
	  ) {
		  
		$Car->save($Helper->cleanInput($_POST["plate"]), $Helper->cleanInput($_POST["color"]));
		
	}
	
	// sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	}else{
		// kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
	}
	
	//kas otsib
	if(isset($_GET["q"])){
		
		$q = $Helper->cleanInput($_GET["q"]);
		
		$carData = $Car->get($q, $sort, $direction);
	
	} else {
		$q = "";
		$carData = $Car->get($q, $sort, $direction);
	
	}
	
	
	
	
?>
<?php require("../header.php"); ?>

<div class="container">

	<h1>Data</h1>
	<?=$msg;?>
	<p>
		Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
		<a href="?logout=1">Logi välja</a>
	</p>


	<h2>Salvesta auto</h2>
	<form method="POST">
		
		<label>Auto nr</label><br>
		<input name="plate" type="text">
		<br><br>
		
		<label>Auto värv</label><br>
		<input type="color" name="color" >
		<br><br>
		
		<input type="submit" value="Salvesta">
		
		
	</form>

	<h2>Autod</h2>

	<form>
		<input type="search" name="q" value="<?=$q;?>">
		<input type="submit" value="Otsi">
	</form>
	
	<?php 
		
		$direction = "ascending";
		if (isset($_GET["direction"])){
			if ($_GET["direction"] == "ascending"){
				$direction = "descending";
			}
		}
		
		$html = "<table class='table table-striped table-bordered'>";
		
		$html .= "<tr>";
			$html .= "<th>
						<a href='?q=".$q."&sort=id&direction=".$direction."'>
							id
						</a>
					</th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=plate&direction=".$direction."'>
							plate
						</a>
					</th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=color&direction=".$direction."'>
							color
						</a>
					</th>";
		$html .= "</tr>";
		
		//iga liikme kohta massiivis
		foreach($carData as $c){
			// iga auto on $c
			//echo $c->plate."<br>";
			
			$html .= "<tr>";
				$html .= "<td>".$c->id."</td>";
				$html .= "<td>".$c->plate."</td>";
				$html .= "<td style='background-color:".$c->carColor."'>".$c->carColor."</td>";
				$html .= "<td>
							<a href='edit.php?id=".$c->id."' class='btn btn-default'>
							
								<span class='glyphicon glyphicon-pencil'></span>
								Muuda
								
							</a>
						</td>";
				
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		
		echo $html;
		
		
		$listHtml = "<br><br>";
		
		foreach($carData as $c){
			
			
			$listHtml .= "<h1 style='color:".$c->carColor."'>".$c->plate."</h1>";
			$listHtml .= "<p>color = ".$c->carColor."</p>";
		}
		
		echo $listHtml;
		
		
		

	?>

	<br>
	<br>
	<br>
	<br>
	<br>
</div>
<?php require("../footer.php"); ?>