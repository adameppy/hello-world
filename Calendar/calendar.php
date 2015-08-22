<!DOCTYPE html>
    <html>
    <head>
        <title>My Calendar</title>
        
        <style type="text/css">
          #loginBox, #logoutBox{
            display: block;
            min-height: 1.75em;
            background-color:#42175C;
            font-family: "Trebuchet MS";
            color: white;
            border: 1.5px solid #;
          }
          /*Gold: BF9646
          Light Gold: EECA84
          Light Purple: B08DC0
          Dark Purple: 67337F
          Darker Purple: 29063F
          Darkerer What We are using for EventBox/LoginBox Purple: 42175C
          Green: 4C9F3B*/
          .inMonthCell{
            background-color:#BF9646;
          }
          .outMonthCell{
            background-color:#EECA84;
          }
          .todayCell{
            background-color:#980503;
          }
          .eventBox{
            background-color:#7C5494;
            border-radius: 10px;
            border: 2px /*solid #No color*/;
          }
          h2{
            color: white;   
          }
          body{
            font-family: "Trebuchet MS";
            background-color:#200049;
          }
          th{
            background-color: #67337F;
            color:white;
          }
          #editEventBox{
            display: block;
            min-height: 4em;
            width:15em;
            margin-left: auto;
            margin-right: auto;
            background-color:#67337F;
            border-radius: 10px;
            font-family: "Trebuchet MS";
            font-size: 13px;
          }
          #completeShareBox{
            display: block;
            min-height: 1.5em;
            width: 28em;
            background-color:#67337F;
            border-radius: 10px;
            font-family: "Trebuchet MS";
            font-size: 15px;
          }
          /*Placeholder Font*/
          input::-webkit-input-placeholder{
            /*For Chrome, Safari*/
            font-family: "Trebuchet MS";
          }
          input::-moz-placeholder{
            /*For Firefox*/
            font-family: "Trebuchet MS";
          }
        </style>
    </head>
    <body>
        <div class="loginBox" id="loginBox">
            <p id="notLoggedIn">
                <label>  Username</label>
                <input type="text" name="username" id="usernameId" placeholder="Username here"/>
                <label> Password </label>
                <input type="password" name="password" id="passwordId"/>
                <button id="loginBtnId">Login</button>
                <button id="registerBtnId">Register</button>
            </p>
        </div>
        <div class="logoutBox" id="logoutBox">
                <label id="usernameP"></label>
                <button id="toggleShareComplete">Completely Share</button>
                <button id="logoutBtn">Logout</button>
                
                <label id="completeLabel">Share complete calendar with: </label>
                <input type="text" id="completeShareId" placeholder="david,adam"/>
                <button id="shareCompleteBtn">Share</button>
        </div>
        
        
        <h2 id="tableTitle"></h2>
        <input type="submit" value="Previous Month" class="prevMonth" id="prevMonth">
        <input type="submit" value="Next Month" class="nextMonth" id="nextMonth">
        <!-- How to escape ""s in PHP: ->   \"        -->
        <table id="calendar" style="width:100%">
            <tr>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
            </tr>
            <!--We will use Javascript to add more rows for the calendar for each day -->
        </table>
        <div id="editEventBox">
            <p>
                <input type="text" id="eventNameId" placeholder="Event Name"/><br />
                <input type="text" id="timeId" placeholder="13:30"/><br />
                <label id="monthDayYearId"> Month Day, Year</label><br />
                Share with other users: <br />
                <input type="text" id="shareId" placeholder="david,adam"/><br />
                <button id="submitEventId">Submit Event</button>
                <button id="closeEventBox">Close</button>
            </p>
        </div>
        <div id="superEventBox">
            <p>
                <input type="text" id="eventNameIdNova" placeholder="Event Name"/><br />
                <input type="text" id="timeIdNova" placeholder="13:30"/><br />
                <label id="monthDayYearIdNova"> Month Day, Year</label><br />
                <button id="submitEventIdNova">Submit Event</button>
                <button id="submitChangesId">Submit Changes</button>
                <button id="deleteId">Delete</button>
                <button id="closeSuperEventBox">Close</button>
            </p>
        </div>
        
        <!--
            <script type="text/javascript" src="miniCalendarLibrary.js"></script>
            <script type="text/javascript" src="event.js"></script>
        -->
        <!--Our Code (src="otherFile" includes the file containing the provided code) -->
        <script type="text/javascript">
            var thePoint = 0;
            //Global Variables
            var thisMonthsEvents = []; //Array of events. This is where we will hold the events for the month we are displaying
            var thisMonth; //the main month displayed
            var selectedDay; //Day clicked on
            var selectedMonth; //Month object clicked on
            var globalUsername = "";
            var today = new Date();
            
            //Main Function, What Will Run When the Page Opens
            function oncePageLoads(){
                //Setting up the login/logout bars
                checkLoginAJAX();
                document.getElementById("editEventBox").style.display="none";
                document.getElementById("superEventBox").style.display="none";
                
                
                //Setting up the calendar table
                today = new Date();
                thisMonth = new Month(today.getFullYear(),today.getMonth()); //So far, will default to March 2015
                loadCalendar(thisMonth);
            }
            thePoint = 1;
            function loadCalendar(inMonth) {
                thisMonth = inMonth; //<-This part is very important to remember.
                document.getElementById("tableTitle").textContent = monthName(inMonth.month) + " " + inMonth.year;
                buildCalendar(inMonth);
                loadEvents(inMonth);
               
            }
            thePoint=2;
            function buildCalendar(inMonth) {
            //This function builds the physical calendar table on the webpage
            //by continuously adding rows to the table representing the calendar
                //.getSunday() has not been tested
                
                 //for (i=0; i<inMonth.getWeeks().length; ++i){
                 
                 //This is clearing the calendar table and adding each of the days of the week.
                document.getElementById("calendar").innerHTML =
                "<tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr>";
                
                var reachedBeginOfMonth = false;
                var reachedEndOfMonth = false;
                var firstDayOfWeekInMonth = parseFloat(inMonth.getDateObject(1).getDay());
                var currentDay = parseFloat(monthLength(monthBeforeFunc(inMonth)))-parseFloat(firstDayOfWeekInMonth); //Current Day should equal first day in the table, which could be a day from another month
                var currentMonth = monthBeforeFunc(inMonth);
                    
                var rowsToGive = 5;
                if ((firstDayOfWeekInMonth==5 || firstDayOfWeekInMonth==6) && inMonth!=1) {
                    rowsToGive = 6;
                } else if (inMonth==1 && firstDayOfWeekInMonth==0 && monthLength(inMonth)==28) {
                    rowsToGive = 4;
                }
                 for (i=0; i<rowsToGive; ++i){//Usually, there are 5 rows in a month
                    var newRow = document.createElement("tr");
                    for (j=0; j<7; ++j){
                        if (!reachedBeginOfMonth && j==firstDayOfWeekInMonth) {
                            //If we've reached the end of the month before the one we are focusing on
                            //Then we change the currentDay and currentMonth to reflect the first of the month
                            //that this monthView is focusing on
                            reachedBeginOfMonth = true;
                            currentDay = 1;
                            currentMonth = monthAfterFunc(currentMonth);
                        } else {
                            //Else, we continue to the next day in that month
                            currentDay+=1;  
                        }
                        if (currentDay>monthLength(currentMonth)) {
                                //If the month we are focusing on now ends. we move on to the next month
                                currentDay = 1;
                                currentMonth = monthAfterFunc(currentMonth);
                        }
                        
                        
                        var newCell = document.createElement("td");
                        //newCell.textContent = currentDay + "\n";
                        newCell.innerHTML = currentDay+" <br />";
                        newCell.setAttribute("id","M"+currentMonth.month+"D"+currentDay+"Y"+currentMonth.year); //This makes that cell easy to access when loading events
                        
                        newCell.addEventListener("click", function(){
                            //Adding an Event to a Day
                            if (globalUsername!="") {
                                //If someone is logged in
                                var newIndex=this.id.lastIndexOf("D");
                                selectedDay = this.id.charAt(newIndex+1);
                                if (this.id.charAt(newIndex+2)!='Y') {
                                    selectedDay = selectedDay + this.id.charAt(newIndex+2);
                                }
                                newIndex=this.id.lastIndexOf("M");
                                var theMonth = this.id.charAt(newIndex+1);
                                if (this.id.charAt(newIndex+2)!='D') {
                                   theMonth = theMonth + this.id.charAt(newIndex+2);
                                }
                                theYear = this.id.substr(this.id.length-4);
                                selectedMonth = new Month(theYear, theMonth);
                                editEventBox.style.display="";
                                document.getElementById("monthDayYearId").textContent=' '+monthName(theMonth)+' '+selectedDay+', '+theYear;
                            } else {
                                alert("Please login to create events");
                            }
                            
                        }, false);
                        
                        //We're coloring each cell. If the cell corresponds to the month that this monthView is focusing on,
                        //we color it a certain color. Else, we color it a different color.
                        if (currentDay==today.getDate()&&currentMonth.month==today.getMonth()&&currentMonth.year==today.getFullYear()) {
                            newCell.setAttribute("class", "todayCell");
                        } else if(currentMonth.month==inMonth.month){
                            newCell.setAttribute("class", "inMonthCell");
                        }else{
                            newCell.setAttribute("class", "outMonthCell");
                        }
                        //Adding the cell to the row
                        newRow.appendChild(newCell);
                    }
                    //Adding the row to the table representing the calendar
                    document.getElementById("calendar").appendChild(newRow);
                }
            }
            thePoint=3;
            function loadEvents(inMonth){
            //This function loads the user's events into the calendar
                if (globalUsername!="") {
                    //Meaning that somebody is logged in
                    requestEvents(inMonth);
                    //Now, all of the events are stored in the array thisMonthsEvents.
		    for(i=0; i<thisMonthsEvents.length; ++i){
                        document.getElementByID("M"+(inMonth.month)+"D"+thisMonthsEvents[i].day).innerHTML += "<p>"+ thisMonthsEvents[i] +"</p>";
                        var newRect = document.createElement("rect");
                        newRect.setAttribute("class","eventBox");
                        
                        newRect.textContent = thisMonthsEvents[i].name + " at " + thisMonthsEvents[i].time;
                        document.getElementById("M"+thisMonth+"D"+thisMonthsEvents[i].day).appendChild(newRect);
                        newRect.addEventListener("click", function(){
                                editEventBox.style.display="";
                                document.getElementById("monthDayYearId").textContent=' '+monthName(thisMonthsEvents[this.id].month)+' '+thisMonthsEvents[this.id].day+', '+thisMonthsEvents[this.id].year;
                                
                                
                                
                        }, true);
                    }
                }
                    
            }
            thePoint=4;
            function requestEvents(inMonth){
                //Pulling out the year and month attributes from inMonth
                var calendarMonth = inMonth.month
                var calendarYear = inMonth.year
                
                //Pulling session variables:
                var username = '<%=Session["username"] %>';
                //alert("requestEvents: beginning");
                //Sending a request
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("POST","getevents.php", true);
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                var dataString = "month="+calendarMonth+"&year="+calendarYear;
                xmlHttp.send(dataString);
                xmlHttp.addEventListener("load", function(event){
                    var jsonData = JSON.parse(event.target.responseText);
                    //alert("requestEvents: EventListener Triggered, received jsonData="+event.target.responseText);
                    var numEvents = jsonData.numberOfEvents;
                    if (numEvents==0) {
                    }
                    for (i=0; i<numEvents; ++i) {
                        eval("var name = jsonData.event"+i+".name");
                        eval("var month = jsonData.event"+i+".month");
                        eval("var day = jsonData.event"+i+".day");
                        eval("var year = jsonData.event"+i+".year");
                        eval("var time = jsonData.event"+i+".time");
                        eval("var duration = jsonData.event"+i+".duration");
                        eval("var event_id = jsonData.event"+i+".event_id");
                        ///alert("The INNER HTML IS: document.getElementByID(M"+inMonth.month+"D"+day+").innerHTML");
                        /////////document.getElementByID("M"+(inMonth.month)+"D"+day).innerHTML += "<p>"+ name + " at " + time + "</p>";
                        //hisMonthsEvents[thisMonthsEvents.length] = aEvent(obj.name, inMonth.month, obj.day, obj.year, obj.time);
                        //alert("thisMonthsEvents[i]: "+thisMonthsEvents[i]);
                        var newRect = document.createElement("rect");
                        newRect.setAttribute("class","eventBox");
                        newRect.setAttribute("id",event_id);
                        newRect.textContent = name + " at "+time;
                        document.getElementById("M"+inMonth.month+"D"+day+"Y"+inMonth.year).appendChild(newRect);
                        var lineBreak = document.createElement("br");
                        document.getElementById("M"+inMonth.month+"D"+day+"Y"+inMonth.year).appendChild(lineBreak);
                        
                        newRect.addEventListener("click", function(){
                            //What happens when you click on a cell in the calendar table
                            //It automatically pops up the editEventBox and stores the selected day, year, month
                            //But only if the person is logged in
                            
                            var xmlHttp = new XMLHttpRequest();
                            xmlHttp.open("POST","geteventbyid.php", true);
                            xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            var dataString = "event_id"+this.id;
                            xmlHttp.send(dataString);
                            xmlHttp.addEventListener("load", function(event){
                             var jsonData = JSON.parse(event.target.responseText);
                            if (jsonData.success) {
                                document.getElementById("superEventBox").style.display="";
                                document.getElementById("eventNameIdNova").value = jsonData.name;
                                document.getElementById("timeIdNova").value = jsonData.time;
                                document.getElementById("monthDayYearIdNova").value = jsonData.month +" " + jsonData.day +", "+ jsonData.year;
                            }
                        else{
                            alert("Connection Error. Please try again later.");
                    }
                    
                }, false);
                            
                            
                            
                            
                            
                        }, false);
                        
                       // alert("Rect should have appeared. textcontent="+name+" at "+time);
                        
                        //Don't know what the line below is for.
                        //console.log(obj.id);
                        //thisMonthsEvents[i].day
                        
                        //alert("Event Added");
                    }
                    //alert("Your file contains the text: " + event.target.responseText);
                    //alert("Loaded. Received the events transmission");
                }, false);
            }
            
            function AddEvent(){
                document.getElementById("submitEventId").textContent = "loading";
                
                var didItConnect = false;
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("POST","addevent_ajax.php", true);
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                var dataString = "day="+selectedDay+"&year="+selectedMonth.year+"&month="+selectedMonth.month+"&name="+document.getElementById("eventNameId").value+"&time="+document.getElementById("timeId").value+"&duration=0";
                xmlHttp.send(dataString);
                xmlHttp.addEventListener("load", function(event){
                    var jsonData = JSON.parse(event.target.responseText);
                    if (jsonData.success) {
                        loadCalendar(thisMonth);
                        document.getElementById("eventNameId").value="";
                        document.getElementById("timeId").value="";
                        document.getElementById("submitEventId").textContent = "Submit";
                        document.getElementById("editEventBox").style.display="none";
                        didItConnect=true;
                    }
                    else{
                        alert("Connection Error. Please try again later.");
                        document.getElementById("submitEventId").textContent = "Submit";
                    }
                    
                }, false);
                if(document.getElementById("shareId").value!=""){
                    //Adding regular event, and then make requests to add a copy of this event to everyone else
                    //Everyone else should get a message that says who requested to add an event.
                    var usersToShare = document.getElementById("shareId").value
                    usersToShare = usersToShare.replace(/\s+/g, '');
                    usersToShare = usersToShare.split(",");
                    for (i=0; i<usersToShare.length; ++i) {
                        var userToShareWithNow = usersToShare[i];
                        var xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("POST","shareevent_ajax.php", true);
                        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        var dataString = "userToShareWith="+userToShareWithNow+"&day="+selectedDay+"&year="+selectedMonth.year+"&month="+selectedMonth.month+"&name="+document.getElementById("eventNameId").value+"&time="+document.getElementById("timeId").value+"&duration=0";
                        xmlHttp.send(dataString);
                        xmlHttp.addEventListener("load", function(event){
                            var jsonData = JSON.parse(event.target.responseText);
                            if (jsonData.success) {
                                document.getElementById("eventNameId").value="";
                                document.getElementById("timeId").value="";
                                document.getElementById("editEventBox").style.display="none";
                            }
                            else{
                                alert("Connection Error or username does not exist.");
                            }
                            
                        }, false);
                        //End of loop of adding that event user1 created to the users he shared the event with
                    }
                }
                
                
            }
            
            
            function shareCompleteCalendar(){
                if(document.getElementById("completeShareId").value!=""){
                    //Adding regular event, and then make requests to add a copy of this event to everyone else
                    //Everyone else should get a message that says who requested to add an event.
                    var usersToShare = document.getElementById("completeShareId").value
                    usersToShare = usersToShare.replace(/\s+/g, '');
                    usersToShare = usersToShare.split(",");
                    for (i=0; i<usersToShare.length; ++i) {
                        var userToShareWithNow = usersToShare[i];
                        var xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("POST","sharecalendar.php", true);
                        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        var dataString = "otheruser="+userToShareWithNow;
                        xmlHttp.send(dataString);
                        xmlHttp.addEventListener("load", function(event){
                            var jsonData = JSON.parse(event.target.responseText);
                            if (jsonData.success) {
                                document.getElementById("toggleShareComplete").style.display="";
                                document.getElementById("completeLabel").style.display="none";
                                document.getElementById("completeShareId").style.display="none";
                                document.getElementById("shareCompleteBtn").style.display="none";
                            }
                            else{
                                alert("Database Error");
                            }
                            
                        }, false);
                        //End of loop of adding that event user1 created to the users he shared the event with
                    }
                } else {
                    alert("Please enter at least one username.");
                }
            }
            
            function monthLength(whichMonth) {
                if (whichMonth.month==0) {
                    return parseFloat(31);
                } else if (whichMonth.month==1){
                    if ((whichMonth.year%4==0 && whichMonth.year%100!=0)||whichMonth.year%400==0){
                        return parseFloat(29);
                    } else {
                        return parseFloat(28);
                    }
                } else if (whichMonth.month==2) {
                    return parseFloat(31);
                } else if (whichMonth.month==3) {
                    return parseFloat(30);
                } else if (whichMonth.month==4) {
                    return parseFloat(31);
                } else if (whichMonth.month==5) {
                    return parseFloat(30);
                } else if (whichMonth.month==6) {
                    return parseFloat(31);
                } else if (whichMonth.month==7) {
                    return parseFloat(31);
                } else if (whichMonth.month==8){
                    return parseFloat(31);
                } else if (whichMonth.month==9) {
                    return parseFloat(31);
                } else if (whichMonth.month==10) {
                    return parseFloat(30);
                } else if (whichMonth.month==11) {
                    return parseFloat(31);
                } else {
                    alert("Error: monthLength. Input whichMonth=("+whichMonth.month+","+whichMonth.year+")");
                    return 0; //This means that something is wrong
                }
            }
            
            function monthName(whichMonth){
                if (whichMonth==0) {
                    return "January";
                } else if (whichMonth==1) {
                    return "February";
                } else if (whichMonth==2) {
                    return "March";
                } else if (whichMonth==3) {
                    return "April";
                } else if (whichMonth==4) {
                    return "May";
                } else if (whichMonth==5) {
                    return "June";
                } else if (whichMonth==6) {
                    return "July";
                } else if (whichMonth==7) {
                    return "August";
                } else if (whichMonth==8) {
                    return "September";
                } else if (whichMonth==9) {
                    return "October";
                } else if (whichMonth==10) {
                    return "November";
                } else if (whichMonth==11) {
                    return "December";
                } else {
                    return "MonthNameError. Value = "+whichMonth;
                }
            }
            
            function monthBeforeFunc(inMonth) {
                if (inMonth.month==0) {
                    return new Month(inMonth.year-1, 11);
                } else {
                    return new Month(inMonth.year, inMonth.month-1);
                }
            }
            
            function monthAfterFunc(inMonth) {
                if (inMonth.month==11) {
                    return new Month(inMonth.year+1, 0);
                } else {
                    return new Month(inMonth.year, inMonth.month+1);
                }
            }
            
            function toTheNextMonth(){
                var newMonth = 0;
                var newYear = thisMonth.year;
                if (thisMonth.month==11) {
                    newMonth = 0;
                    newYear += 1;
                } else {
                    newMonth = thisMonth.month+1;
                }
                loadCalendar(new Month(newYear, newMonth));
            }
            
            function toThePrevMonth(){
                var newMonth = 0;
                var newYear = thisMonth.year;
                if (thisMonth.month==0) {
                    newMonth = 11;
                    newYear -= 1;
                } else {
                    newMonth = thisMonth.month-1;
                }
                loadCalendar(new Month(newYear, newMonth));
            }
            
            function checkLoginAJAX() {
                var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
                xmlHttp.open("POST", "checklogin_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
                xmlHttp.addEventListener("load", function(event){
                    var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
                    if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			globalUsername = jsonData.username;
                        document.getElementById("usernameP").textContent = username;
                        document.getElementById("loginBox").style.display="none"; 
                        document.getElementById("logoutBox").style.display="";
                    }else{
			document.getElementById("loginBox").style.display=""; 
                        document.getElementById("logoutBox").style.display="none";
                        username="";
                        thisMonthsEvents=[];
                    }
                }, false); // Bind the callback to the load event
                xmlHttp.send(null); // Send the data
            }
            
            function loginAJAX() {
                var username = document.getElementById("usernameId").value;
                var password = /*Security*/document.getElementById("passwordId").value;
                    //Sending a request and listening for the response and the .php file to finish running
                    var xmlHttp = new XMLHttpRequest();
                    xmlHttp.open("POST","LoggingInJSON.php", true);
                    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    var dataString = "username="+encodeURIComponent(username)+"& password="+encodeURIComponent(password);
                    xmlHttp.addEventListener("load", function(event){
                        var jsonData = JSON.parse(event.target.responseText);
                        if (jsonData.success) {
                            document.getElementById("usernameP").textContent = username;
                            globalUsername=username;
                            document.getElementById("loginBox").style.display="none"; 
                            document.getElementById("logoutBox").style.display="";
                            document.getElementById("toggleShareComplete").style.display="";
                            document.getElementById("completeLabel").style.display="none";
                            document.getElementById("completeShareId").style.display="none";
                            document.getElementById("shareCompleteBtn").style.display="none";
                            loadCalendar(thisMonth);
                            document.getElementById("usernameId").value="";
                            document.getElementById("passwordId").value=="";
                        } else {
                            alert(jsonData.message);
                            document.getElementById("passwordId").value=="";
                        }
                    }, false);
                    xmlHttp.send(dataString);
            }
            
            function registerAJAX(args) {
                
                //STORE CONTENT OF LOGIN/PASSWORD BOXES...
                var username = document.getElementById("usernameId").value;
                var password = /*Security*/document.getElementById("passwordId").value;
                
                if (username=="") {
                    alert("Invalid Username. Please try another.")
                } else {
                
                //Sending a request and listening for the response and the .php file to finish running
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("POST","register_ajax.php", true);
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                var dataString = "username="+encodeURIComponent(username)+"& password="+encodeURIComponent(password);
                xmlHttp.addEventListener("load", function(event){
                    var jsonData = JSON.parse(event.target.responseText);
                    if (jsonData.success) {
                        document.getElementById("usernameP").textContent = username;
                        globalUsername = username;
                        document.getElementById("loginBox").style.display="none";
                        document.getElementById("logoutBox").style.display="";
                        document.getElementById("toggleShareComplete").style.display="";
                        document.getElementById("completeLabel").style.display="none";
                        document.getElementById("completeShareId").style.display="none";
                        document.getElementById("shareCompleteBtn").style.display="none";
                        
                        document.getElementById("usernameId").value="";
                        document.getElementById("passwordId").value=="";
                        loadCalendar(thisMonth);
                    } else {
                        alert(jsonData.message);
                        document.getElementById("passwordId").value;
                    }
                }, false);
                xmlHttp.send(dataString);
                }
            }
            
            function logoutAJAX(args) {
                //Sending a request and listening for the response and the .php file to finish running
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("POST","register_ajax.php", true);
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlHttp.send(null);
                
                document.getElementById("toggleShareComplete").style.display="";
                document.getElementById("completeLabel").style.display="none";
                document.getElementById("completeShareId").style.display="none";
                document.getElementById("shareCompleteBtn").style.display="none";
                document.getElementById("loginBox").style.display="";
                document.getElementById("logoutBox").style.display="none";
                document.getElementById("editEventBox").style.display="none";
                
                
                document.getElementById("usernameP").textContent = "";
                document.getElementById("passwordId").value="";
                globalUsername="";
                thisMonthsEvents=[];
                loadCalendar(thisMonth);
                
            }
            
            
            //When Next Month is Pressed
                //loadCalendar(next)
                    //buildCalendar(Next)
                    //loadEvents(Next)
                    //nowMonth = NextMonth
            
            //When Prev Month is Pressed
                //loadCalendar(Prev)
                    //buildCalendar(Prev)
                    //loadEvents(Prev)
                    //NowMonth = PrevMonth
                
            //Create Event
                //databaseConnect(createEvent, thisEvent)
                //loadCalendar(Now)
            
            //Delete Event
                //databaseConnect(deleteEvent)
                //loadCalendar(Now)
            
            //Possible Creative Portion:
                //Sharing events with friends-acceptable
                //We need more than one added function to get the creative portion points
            thePoint = 6;
            
            document.addEventListener("DOMContentLoaded", oncePageLoads, false);
            
            document.getElementById("prevMonth").addEventListener("click", toThePrevMonth, false);
            document.getElementById("nextMonth").addEventListener("click", toTheNextMonth, false);
            
            document.getElementById("loginBtnId").addEventListener("click", loginAJAX, false);
            document.getElementById("registerBtnId").addEventListener("click", registerAJAX, false);
            document.getElementById("logoutBtn").addEventListener("click", logoutAJAX, false);
            document.getElementById("submitEventId").addEventListener("click", AddEvent, false);
            document.getElementById("closeEventBox").addEventListener("click", function(){
                document.getElementById("editEventBox").style.display="none";
            }, false);
            document.getElementById("toggleShareComplete").addEventListener("click", function(){
                document.getElementById("toggleShareComplete").style.display="none";
                document.getElementById("completeLabel").style.display="";
                document.getElementById("completeShareId").style.display="";
                document.getElementById("shareCompleteBtn").style.display="";
            }, false);
            document.getElementById("shareCompleteBtn").addEventListener("click", shareCompleteCalendar, false);
            document.getElementById("closeSuperEventBox").addEventListener("click", function(){
                document.getElementByID("superEventBox").style.display="none";
            }, false);
            
            //Definition of an Event Object:
            //Defining an event object (we called it aEvent because event is an object already programmed into javascript)
function aEvent(name, month, day, year, time) {
    "use strict";
                
    this.name = name;
    this.month = month;
    this.day = day;
    this.year = year;
    this.time = time;
}

            //Provided Code:
            
/* * * * * * * * * * * * * * * * * * * *\
 *               Module 4              *
 *      Calendar Helper Functions      *
 *                                     *
 *        by Shane Carr '15 (TA)       *
 *  Washington University in St. Louis *
 *    Department of Computer Science   *
 *               CSE 330S              *
 *                                     *
 *      Last Update: October 2012      *
\* * * * * * * * * * * * * * * * * * * */

/*  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function () {
	"use strict";

	/* Date.prototype.deltaDays(n)
	 * 
	 * Returns a Date object n days in the future.
	 */
	Date.prototype.deltaDays = function (n) {
		// relies on the Date object to automatically wrap between months for us
		return new Date(this.getFullYear(), this.getMonth(), this.getDate() + n);
	};

	/* Date.prototype.getSunday()
	 * 
	 * Returns the Sunday nearest in the past to this date (inclusive)
	 */
	Date.prototype.getSunday = function () {
		return this.deltaDays(-1 * this.getDay());
	};
        
        //The following inner-function is created by Adam Epstein and David Schonfeld
        /*
        Date.prototype.getDay = function (){
            return this.day;
        }
        */
}());

/** Week
 * 
 * Represents a week.
 * 
 * Functions (Methods):
 *	.nextWeek() returns a Week object sequentially in the future
 *	.prevWeek() returns a Week object sequentially in the past
 *	.contains(date) returns true if this week's sunday is the same
 *		as date's sunday; false otherwise
 *	.getDates() returns an Array containing 7 Date objects, each representing
 *		one of the seven days in this month
 */
function Week(initial_d) {
	//"use strict";
        
	//this.sunday = initial_d.getSunday();
        //I moved the defintion of .getSunday() below so the line above works
        //alert("initial_d is "+initial_d);
        this.sunday =  initial_d.deltaDays(-1 * initial_d.getDay());
        
		
	
	this.nextWeek = function () {
		return new Week(this.sunday.deltaDays(7));
	};
	
	this.prevWeek = function () {
		return new Week(this.sunday.deltaDays(-7));
	};
	
	this.contains = function (d) {
		return (this.sunday.valueOf() === d.getSunday().valueOf());
	};
	
	this.getDates = function () {
		var dates = [];
		for(var i=0; i<7; i++){
			dates.push(this.sunday.deltaDays(i));
		}
		return dates;
	};
}

/** Month
 * 
 * Represents a month.
 * 
 * Properties:
 *	.year == the year associated with the month
 *	.month == the month number (January = 0)
 * 
 * Functions (Methods):
 *	.nextMonth() returns a Month object sequentially in the future
 *	.prevMonth() returns a Month object sequentially in the past
 *	.getDateObject(d) returns a Date object representing the date
 *		d in the month
 *	.getWeeks() returns an Array containing all weeks spanned by the
 *		month; the weeks are represented as Week objects
 */
function Month(year, month) {
	"use strict";
	
	this.year = year;
	this.month = month;
	
	this.nextMonth = function () {
		return new Month( year + Math.floor((month+1)/12), (month+1) % 12);
	};
	
	this.prevMonth = function () {
		return new Month( year + Math.floor((month-1)/12), (month+11) % 12);
	};
	
	this.getDateObject = function(d) {
		return new Date(this.year, this.month, d);
	};
	
	this.getWeeks = function () {
		var firstDay = this.getDateObject(1);
                alert("First Day: "+firstDay);
		var lastDay = this.nextMonth().getDateObject(0);
		alert("Last Day: "+lastDay);
                
		var weeks = [];
		var currweek = new Week(firstDay);
		weeks.push(currweek);
		while(!currweek.contains(lastDay)){
			currweek = currweek.nextWeek();
			weeks.push(currweek);
		}
		
		return weeks;
	};
}
    
        </script>
        
        
        
    </body>
</html>