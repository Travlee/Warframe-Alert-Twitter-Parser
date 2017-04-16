
<?php 
    
    //  This script saves twitter data to a file on the server,
    //      to cap calls to once per minute. No client overrides. 
    //  Returns either latest twitter-data, by pulling new data,
    //      or last_checked data.

    //  CONST <String> URL for @WarframeAlerts Twitter Account
    // define("URL", "http://www.twitter.com/warframealerts0/");
    define("URL", "http://192.168.1.102");
    
    //  CONST <String> URI for Local Alerts File
    define("ALERTS_FILE", "Alerts.json");
    
    //  CONST <Int> Milisecond Delay for Update Calls
    define("UPDATE_DELAY", 60000);
    
    class Alerts{
        public $LAST_CHECKED = 0;
        public $ALERTS = [];
    }
    
    // WarFrame Lib Class
    class WarFrame{
        
        //  Loads Data from JSON File 
        static function Get_File(){
            if(file_exists(ALERTS_FILE)){
                $temp = file_get_contents(ALERTS_FILE);
                return json_decode($temp, true);
            } else {
                echo "Error: can't find file";
            }
        }

        //  Saves Data to JSON File
        //  ARGS:
        //      0: Time in Seconds; Time Last Checked for Alerts
        //      1: Alerts as an <Object> Array
        static function Save_File($time, $alerts){
            if(file_exists(ALERTS_FILE)){
                $last_checked = "";
                $data = json_encode($alerts);
            } else {
                echo "Error: can't find file";
            }
        }
        
        //  Pulls New HTML-Data From Twitter
        static function Pull_Data(){
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, URL);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($handle);
            $errors = curl_error($handle);
            if($errors){
                echo $errors;
            } else {
                return $data;
            }
            curl_close($handle);
        }
        
        //  Parses Twitter Data, then returns Active Alerts
        static function Parse_Alerts(){}

    }
    
    //  Base Class for Alerts
    class Alert{
        public $Location = '';
        public $Mission = '';
        public $Start = '';
        public $End = '';
        public $Credits = '';
        public $Reward_Type = '';
        public $Reward = '';
        
        public function __contructor($location, $mission, $start,
                $end, $credits, $reward_type, $reward){
            $this->Location = $location;
            $this->Mission = $mission;
            $this->Start = $start;
            $this->End = $end;
            $this->Credits = $credit;
            $this->Reward_Type = $reward_type;
            $this->Reward = $reward;
        }
    }
    
    function Main(){
        
        //  <Int> Stores Current Time in seconds.
        $Time = time();
        
        //  Stores Data from JSON File
        $Alerts = WarFrame::Get_File();
        
        //  Test
        //  - Runs Else case for Testing reasons.
        $Alerts['LAST_CHECKED'] = $Time;
        
        //  Debug
        $Alert_Obj = new Alerts;
        echo json_encode($Alert_Obj);

        //  $Alerts['LAST_CHECKED'] = Last Data-Pull in Seconds 
        //  Check if its time to pull new Data
        if($Alerts['LAST_CHECKED'] + UPDATE_DELAY < time()){
            echo "PULLING NEW DATA...\n";
            $data = WarFrame::Pull_Data();
            
            //  Parse Twitter Data
            
            
            echo $data;
        } 
        //  Else return JSON <String> of Last Alerts
        else {
            //  Encodes as JSON String for Echoing
            echo json_encode($Alerts);
        }    
    }
    
    Main();
?>