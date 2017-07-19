<?php
/**
 * index.php.php along with survey_view.php provides a sample web application
 *
 * The difference between demo_list.php and index.php.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple  
 * pages. 
 *
 * The associated view page, survey_view.php is virtually identical to demo_view.php. 
 * The only difference is the pager version links to the list pager version to create a 
 * separate application from the original list/view. 
 * 
 * @package surveysez
 * @author Zak Brinlee <zbrinlee@gmail.com>
 * @version 0.1 2017/07/17
 * @link http://www.programmingbyzakb.com/itc250/sm17/surveys/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see index.php
 * @todo none
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to index.php.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php.php");
}

$mySurvey = new Survey($myID);

if($mySurvey->isValid)
{#only load data if record found
	$config->titleTag = $mySurvey->Title . " surveys made with PHP & love!"; #overwrite PageTitle with Muffin info!
}

get_header(); #defaults to theme header or header_inc.php

 echo '<h3 align="center">Surveys</h3>';


if($mySurvey->isValid)
{#records exist - show survey!
    echo'
	<h3>' . $mySurvey->Title . '</h3>
    <p>' . $mySurvey->Description . '</p>
    ';

}else{//no such survey!
    echo '<div align="center">What! No such survey? There must be a mistake!!</div>';
}
    echo '<div align="center"><a href="' . VIRTUAL_PATH . 'surveys/index.php">Back</a></div>';

get_footer(); #defaults to theme footer or footer_inc.php


class Survey
{

    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $isValid = false;
    
    public function __construct($id)
    {
        $id = (int)$id;//cast to integer disallows sql injection
        $sql = "select Title,Description from sm17_surveys where SurveyID = " . $id;
          
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
            
            $this-> isValid = true;//record found
            
           while ($row = mysqli_fetch_assoc($result))
           {
                $this->Title = dbOut($row['Title']);
                $this->Description = dbOut($row['Description']);
           }
        }

        @mysqli_free_result($result); # We're done with the data!        
    }//end of constructor
    
}//end of Survey class


