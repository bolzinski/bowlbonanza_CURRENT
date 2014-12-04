<?php

/* THIS CODE IS BASED ON A FEED I FOUND AND IS DEPENDENT ON THE DATA BEING PRESENTED IN THIS FORMAT, NOTE THE TEAM NAMES WILL IMPACT HOW THE SCORE IS PARSED

Array ( [0] => Nevada [1] => 48 [2] => [3] => [4] => Arizona [5] => 49 [6] => (FINAL) ) 
Array ( [0] => Toledo [1] => 15 [2] => [3] => [4] => Utah [5] => State [6] => 41 [7] => (FINAL) ) 
Array ( [0] => Brigham [1] => Young [2] => 23 [3] => [4] => [5] => San [6] => Diego [7] => State [8] => 6 [9] => (FINAL) ) 
Array ( [0] => Ball [1] => State [2] => 17 [3] => [4] => [5] => UCF [6] => 38 [7] => (FINAL) ) 
Array ( [0] => East [1] => Carolina [2] => 34 [3] => [4] => [5] => Louisiana-Lafayette [6] => 43 [7] => (FINAL) ) 
Array ( [0] => Washington [1] => 26 [2] => [3] => [4] => Boise [5] => State [6] => 28 [7] => (FINAL) ) 
Array ( [0] => Fresno [1] => State [2] => 10 [3] => [4] => [5] => Southern [6] => Methodist [7] => 43 [8] => (FINAL) ) 
Array ( [0] => Western [1] => Kentucky [2] => 21 [3] => [4] => [5] => Central [6] => Michigan [7] => 24 [8] => (FINAL) ) 
Array ( [0] => San [1] => Jose [2] => State [3] => 29 [4] => [5] => [6] => Bowling [7] => Green [8] => 20 [9] => (FINAL) ) 
Array ( [0] => Cincinnati [1] => 48 [2] => [3] => [4] => Duke [5] => 34 [6] => (FINAL) ) 
Array ( [0] => Baylor [1] => 49 [2] => [3] => [4] => UCLA [5] => 26 [6] => (FINAL) ) 
Array ( [0] => Ohio [1] => 45 [2] => [3] => [4] => Louisiana-Monroe [5] => 14 [6] => (FINAL) ) 
Array ( [0] => Rutgers [1] => 10 [2] => [3] => [4] => Virginia [5] => Tech [6] => 13 [7] => (FINAL [8] => - [9] => OT) ) 
Array ( [0] => Minnesota [1] => 31 [2] => [3] => [4] => Texas [5] => Tech [6] => 34 [7] => (FINAL) ) 
Array ( [0] => Rice [1] => 33 [2] => [3] => [4] => Air [5] => Force [6] => 14 [7] => (FINAL) ) 
Array ( [0] => West [1] => Virginia [2] => 14 [3] => [4] => [5] => Syracuse [6] => 38 [7] => (FINAL) ) 
Array ( [0] => Navy [1] => 28 [2] => [3] => [4] => Arizona [5] => State [6] => 62 [7] => (FINAL) ) 
Array ( [0] => Texas [1] => 31 [2] => [3] => [4] => Oregon [5] => State [6] => 27 [7] => (FINAL) ) 
Array ( [0] => TCU [1] => 16 [2] => [3] => [4] => Michigan [5] => State [6] => 17 [7] => (FINAL) ) 
Array ( [0] => North [1] => Carolina [2] => State [3] => 24 [4] => [5] => [6] => Vanderbilt [7] => 38 [8] => (FINAL) ) 
Array ( [0] => USC [1] => 7 [2] => [3] => [4] => Georgia [5] => Tech [6] => 21 [7] => (FINAL) ) 
Array ( [0] => Iowa [1] => State [2] => 17 [3] => [4] => [5] => Tulsa [6] => 31 [7] => (FINAL) ) 
Array ( [0] => LSU [1] => 24 [2] => [3] => [4] => Clemson [5] => 25 [6] => (FINAL) ) 
Array ( [0] => Mississippi [1] => State [2] => 20 [3] => [4] => [5] => Northwestern [6] => 34 [7] => (FINAL) ) 
Array ( [0] => Purdue [1] => 14 [2] => [3] => [4] => Oklahoma [5] => State [6] => 58 [7] => (FINAL) ) 
Array ( [0] => Georgia [1] => 45 [2] => [3] => [4] => Nebraska [5] => 31 [6] => (FINAL) ) 
Array ( [0] => South [1] => Carolina [2] => 33 [3] => [4] => [5] => Michigan [6] => 28 [7] => (FINAL) ) 
Array ( [0] => Wisconsin [1] => 14 [2] => [3] => [4] => Stanford [5] => 20 [6] => (FINAL) ) 
Array ( [0] => Northern [1] => Illinois [2] => 10 [3] => [4] => [5] => Florida [6] => State [7] => 31 [8] => (FINAL) ) 
Array ( [0] => Louisville [1] => 33 [2] => [3] => [4] => Florida [5] => 23 [6] => (FINAL) ) 
Array ( [0] => Oregon [1] => 35 [2] => [3] => [4] => Kansas [5] => State [6] => 17 [7] => (FINAL) ) 
Array ( [0] => Texas [1] => A%26M [2] => at [3] => Oklahoma [4] => (FRI, [5] => JAN [6] => 4 [7] => 8:00 [8] => PM [9] => ET) ) 
Array ( [0] => Pittsburgh [1] => at [2] => Ole [3] => Miss [4] => (SAT, [5] => JAN [6] => 5 [7] => 1:00 [8] => PM [9] => ET) ) 
Array ( [0] => Kent [1] => State [2] => at [3] => Arkansas [4] => State [5] => (SUN, [6] => JAN [7] => 6 [8] => 9:00 [9] => PM [10] => ET) ) 
Array ( [0] => Notre [1] => Dame [2] => at [3] => Alabama [4] => (MON, [5] => JAN [6] => 7 [7] => 8:00 [8] => PM [9] => ET) ) 

*/


require_once("helper_error.php");
require_once("helper_mail.php");
require_once("lib/participant_class.php");
require_once("lib/validate_class.php");
require_once("lib/validator_helper_class.php");

define('BB_RESULTS_PAGE', 'results.php');
define('BB_STANDINGS_PAGE', 'view_entries.php');
define('NUMBER_OF_BOWL_GAMES', 35);

function readSzBlog() { 

    $data = file_get_contents("http://bb2.olzinski.com/rss/ncaa_cf.php"); 
     
    //print simplexml_load_string($data);
	//var_dump($data);
	//exit;
	
	//print $data;
	$x = simplexml_load_string($data); 
	//var_dump($data);
    //exit;
    $title = array(); 
	
	//$titles = $data->getElementsByTagName("title");
	$p_participant = new participant();
	
	foreach($x->channel->item as $item) {
            
     	//print "title: " . (string) $item->title . "<br />"; 
		//$title = explode(" ",(string) $item->title);
		$title = (string) $item->title;
		//$title_exp = array_filter(explode(" ", $title));
		$title_exp = preg_split ("/\s+/", $title);
		if (stristr($title,'FINAL') == TRUE) {
			//print "team 1: " . $title_exp[0] . " - team 2: " . $title_exp[4] . "<br />";
			//print_r ($title_exp);
			print $title . "<br />";
			$team_01="";
			$team_02="";
			$team_01_score = 0;
			$team_02_score = 0;
			$score_idx_prev = 0;
			$k_idx_start = 0;
			for ($i=0;$i<count($title_exp);$i++) {
				if (is_numeric($title_exp[$i])) {
					for ($k=$k_idx_start;$k<$i;$k++) {
					  switch ($score_idx_prev) {
						case 0:
							$team_01_score  = $title_exp[$i];
							$team_01 = $team_01 . " " . $title_exp[$k];
							break ;
						default:
							$team_02_score  = $title_exp[$i];
							$team_02 = $team_02 . " " . $title_exp[$k];
							break;
					  }  //end switch
					} //end for - build team name
					$score_idx_prev = $i;
					$k_idx_start = $i + 1;
				} // END IF
			} // END FOR - EXTRACT FEED TEAM NAMES AND SCORES
			$p_participant->result_list = NULL;
			$team_01 = urldecode(ltrim($team_01));
			$team_02 = urldecode(ltrim($team_02));
			$r_bowl_winner = "";
			$bowl_winner = "";
			$p_participant->get_bowl_by_teams($team_01, $team_02);
			switch ($p_participant->sp_return_code) {
			  case 0:
			  	  $row_count = sizeof($p_participant->result_list);
			  	  for ( $h=0; $h<$row_count; $h++) { 
				  	$r_bowl_id = $p_participant->result_list[$h]['bowl_id'];
					$r_bowl_winner = $p_participant->result_list[$h]['bowl_winner'];
				  } // END FOR
				  break ;
			  default:
				  write_to_error_log("process_rss.php: sp_get_bowl_by_teams(" .  $team_01 . " , " . $team_02 . ") returned 0 rows!");
				  print "process_rss.php: sp_get_bowl_by_teams(" .  $team_01 . " , " . $team_02 . ") returned 0 rows!<br />";
				  //print "message: " . $p_participant->msg . "<br>";
				  break;
			}  //end switch
			if ($r_bowl_winner == "TBD") { //ONLY UPDATE BOWL IF WINNER NOT ALREADY SET
			  if (((int)$team_01_score) > ((int)$team_02_score)) {
				  $bowl_winner = $team_01;
			  }
			  else {
				  $bowl_winner =$team_02;
			  }
			  $p_participant->update_bowl_winner($r_bowl_id, $bowl_winner);
			  switch ($p_participant->sp_return_code) {
				case 0:
				case 1:
					break ;
				case 106202:
					write_to_error_log("process_rss.php: error# 106202 - sp_update_bowl_winner(" .  $r_bowl_id . " , " .$bowl_winner . ")");
				    print "process_rss.php: error# 106202 - sp_update_bowl_winner(" .  $r_bowl_id . " , " .$bowl_winner . ")<br />";
					break ;
				default:
					write_to_error_log("process_rss.php: error# UNKNOWN - sp_update_bowl_winner(" .  $r_bowl_id . " , " .$bowl_winner . ")");
				    print "process_rss.php: error# UNKNOWN - sp_update_bowl_winner(" .  $r_bowl_id . " , " .$bowl_winner . ")<br />";
					break;
			  }  //end switch
			  
			  
			} //END IF 
			print $team_01 . " " . $team_01_score . " " . $team_02 . " " . $team_02_score  . "<br />";
			print $bowl_winner . " WON!! <br />";
	  } // END IF final score check
		
	} // END FOR EACH
    
	exit;
   /* foreach($data->entry as $entry) {            
     	
        $entries[] = array ('title' => (string)$entry->title);
		
    }*/
	
    //$first = $entries[0]; 
    //return($first); 
         
};  

readSzBlog();

?>

<?php 
/*
foreach(readSzBlog() as $post) 
    {  
?> 
    <h2><?php echo($post['title']); ?></h2> 
    <div><?php echo($post['summary']); ?></div> 
<?php 
    } 
*/
?>
