<?php 

class __session{
	
	
	public function init() {
		
		$result  = array();
		
		$result['initialized']=false;
		$_SESSION['CREATED'] = time();
		$result['initialized']=true;
		
		return $result;
	}

	public function check($Global){
		
		$result  = array();
		$result['expired']=false;
		if (!isset($_SESSION['CREATED'])) {
			$_SESSION['CREATED'] = time();
		}
		else if (time() - $_SESSION['CREATED'] > $Global->endSessionTime()) {
			$result['expired']=true;
		}
		
		return $result;
		
	}
	

}

?>