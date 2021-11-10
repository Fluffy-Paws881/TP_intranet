<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>intranet prof</title>
	<style type="text/css">
		#name{
			font-style: italic; 
			font-size: 4em;
			 margin: auto;
  			width: 50%;
  			border: 3px solid green;
  			padding: 10px;
  			text-align: center;
  			display: block;
			}
		#tableau,th,td{
			border: 1px solid black;
			width: 30%;
			margin: auto;

		}
	</style>
</head>
<body>
	<script type="text/javascript">
			function setEnable(i){
				if (document.getElementById("inp"+i).disabled == false) {
					document.getElementById("inp"+i).disabled = true;
				}else if(document.getElementById("inp"+i).disabled == true){
					document.getElementById("inp"+i).disabled = false;
				}
			

			}

	</script>


<div id='name' > prof </div>
	<?php 

	 	$db_username = 'root';
	    $db_password = '';
	    $db_name     = 'intranet';
	    $db_host     = 'localhost';

	    try {
	    	 $db = mysqli_connect($db_host, $db_username, $db_password,$db_name);
	    }catch (mysqli_sql_exeption $e){
	    	echo 'erreur: '.$e->getMessage();
	    }


				  if(isset($_POST['edi']) && isset($_POST['inp']) ){
				  	$data1 = $_POST['edi'];
				  	$data2 = $_POST['inp'];

				  for ($i=0; $i < count($data2); $i++) { 
				  	foreach ($data1 as $row ) {
				  		
				  		$recup[$i] = explode(",", $data1[$i]);

				  		$req = "SELECT distinct note_eleve.id as id FROM identifiant,note_eleve where note_eleve.id = identifiant.id AND identifiant.nom ='".$recup[$i][0]."' ";
				  		$result = $db->query($req);
				  		$id = mysqli_fetch_array($result);
    					//var_dump($id['id']);
    					
    					//recup l identifiant dans la where 
    					$req = "UPDATE note_eleve SET note = '".$data2[$i]."' WHERE id = '".$id['id']."' AND id_eval ='".$recup[$i][1]."'";
    					
    					 $stmt = mysqli_prepare($db,$req);
    					 $stmt->execute();
    				}
				  }
				  
				  //var_dump($recup);
				 }
    				

    			/*if(isset($_POST['inp']) ){
				  	$data = $_POST['inp'];
    				foreach ($data as $row ) {
    					var_dump($row);
    				}
    			}*/
			    
				//}

	     $requete = "SELECT  evaluation.id_matiere as id_matiere ,note_eleve.id_eval as id_eval, identifiant.nom as nom, identifiant.prenom as prenom, note_eleve.note as note FROM identifiant,note_eleve,evaluation where note_eleve.id_eval = evaluation.id_eval AND identifiant.id = note_eleve.id ORDER by id_matiere";


				
				$result = $db->query($requete);
				
				//generation du tableau de note de tout les eleves
				echo "<form method='post' action='#'><table id='tableau'> ";
				echo " 	<tr>
            				<th colspan='6'>NOTE ELEVES</th>
        				</tr>
						<tr>
		    				<th>matiere</th>
		    				<th>evaluation</th>
		    				<th>nom</th>
		    				<th>prenom</th>
		    				<th>note</th>
		    				<th>editer</th>
    					</tr>";
    					$i = 0;
				foreach ($result as $row) {
					echo "  <tr>
		    					<td>".$row['id_matiere']."</th>
		    					<td>".$row['id_eval']."</th>
		    					<td>".$row['nom']."</th>
		    					<td>".$row['prenom']."</th>
		    					<td>".$row['note']." <input type='number' id ='inp".$i."'name='inp[]' disabled ></th>
		    					<td><input type='checkbox' id='check".$i."' name='edi[]' value =".$row['nom'].",".$row['id_eval']." onclick = 'setEnable(".$i.")' ></th>
    						</tr>";
					$i++;
	
				}

				echo "</table><input  type='submit' value='MODIFIER'></form> ";

				$requete = "SELECT distinct evaluation.id_matiere as id_matiere , identifiant.nom as nom, identifiant.prenom as prenom, round(AVG(note_eleve.note),2) as moyenne FROM identifiant,note_eleve,evaluation where 
              			  note_eleve.id_eval = evaluation.id_eval AND identifiant.id = note_eleve.id group by nom,id_matiere ORDER by id_matiere";


              			  $result = $db->query($requete);
				
				//generation du tableau de moyenne

				echo "<table id='tableau'> ";
				echo " 	<tr>
            				<th colspan='4'>MOYENNE ELEVES</th>
        				</tr>
						<tr>
		    				<th>matiere</th>
		    				<th>nom</th>
		    				<th>prenom</th>
		    				<th>moyenne</th>
    					</tr>";

				foreach ($result as $row) {
					echo "  <tr>
		    					<td>".$row['id_matiere']."</th>
		    					<td>".$row['nom']."</th>
		    					<td>".$row['prenom']."</th>
		    					<td>".$row['moyenne']."</th>
    						</tr>";
					
				}
				echo "</table> ";




				
	 ?>

</body>
</html>