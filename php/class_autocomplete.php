<?php
	
class __autocomplete {

		function dbopen($con,$conf){
			//$con = mysqli_connect($conf['host'], $conf['user'], $conf['password']) or die('Error DB Connect: ' . mysql_error());
			mysqli_select_db($con,$conf['db']) or die('Error DB Select: ' . mysqli_error($con));
			mysqli_set_charset($con,'utf8');
		}

		function dbQuery($sql,$field,$idField,$con) {
			$zeilen = array();
			$zeile = array();
			$query = mysqli_query($con,$sql) or die('Error SQL: ' . mysql_error());
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
				$zeile['value'] = $row[$field];
				$zeile['label'] = $row[$field];
				$zeile['ID'] = $row[$idField];
				$zeilen[] = $zeile;
			}
			return $zeilen;
		}

		function readStationFrom($term,$con) {
			$sql = 
				   "
				   	SELECT  
				 	DISTINCT
						 	  route_station_details.stationFromID, 
						 	  station.name 
					FROM 	  route_station_details
					JOIN 	  station on route_station_details.stationFromID = station.ID
					WHERE 	  station.name LIKE '$term%'
					ORDER BY  station.name
				   ";
			
			return $this->dbQuery($sql, 'name','stationFromID',$con);
		}
				
		function readStationTo($term,$idC,$con) {
			
			$sql =
				   "
					SELECT 
					DISTINCT 
							  route_station_details.stationFromID,
							  route_station_details.stationToID, 
							  station.name 
					FROM 	  route_station_details
					JOIN 	  station on route_station_details.stationToID = station.ID
					WHERE 	  station.name LIKE '$term%' AND (route_station_details.stationFromID='$idC');
		   		   ";
		
			return $this->dbQuery($sql, 'name','stationToID',$con);
		}
}
	
?>		