//  WarFrame Alerter 
//  Last Update: 11-04-2015
//      - Web Scraper Used to Pull Tweets from WarFrame's Twitter to 
//          List All Active Alerts as well as Their Rewards.
var WarFrame = {
    
    //  CONST URI for PHP Twitter Scraper
    SCRIPT: '/Pages/Projects/Web/WarFrame_Alerter/Scripts/WarFrame_Alerter.php',
    
    //  CONST <Int> Update Interval(ms) for New Alert Check Calls
    UPDATE_INTERVAL: 30000,
    
    //  - Calls PHP Script that returns JSON Alert <String> 
    //  RETURNS: <String> JSON Alert Data
    Get_Alerts: function(){
        
        Site.Load_File(WarFrame.SCRIPT, function(){
            
            //  Response Handling
            var temp = this.responseText;
            console.log(temp);
            return temp;
            
        });
        
    },
    
    //  - Parses HTML Tweet-Data
    //  RETURNS: <String> JSON Active Alert Data
    // Parse_Alerts: function(){}
    
    //  - Creates Layout for Alerts
    Create_Layout: function(alert_string){
        
        //  Parses JSON STRING to Object
        var Alerts_Obj = JSON.parse(alert_string);
        var Last_Checked = Alerts_Obj.LAST_CHECKED;
        var Alerts = Alerts_Obj.ALERTS;
        
        //  Loops Through all ALerts
        for(var Alert in Alerts){
            console.log(Alerts[Alert]);
        }
        
        
    }
};

function Main(){
    
	var Alerts = WarFrame.Get_Alerts();
    (function loop(){
       if(!Alerts){
           requestAnimationFrame(loop);
       } else {
           
            //  Creates Layout for Alerts     
           WarFrame.Create_Layout(Alerts);
       }
    })();
    
}

//  Browser Support Checking; Waits for DOM Loaded 
(function(){
    if(!window.requestAnimationFrame){
        console.error('No RAF');
    } else {
        if(document.readyState === 'complete') Main();
        else document.addEventListener('DOMContentLoaded', Main, false);
    }
})();