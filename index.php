<!DOCTYPE html>
<html>
<head>
    <title>Event Calendar</title>
	<!-- stylesheet -->
    	<link type="text/css" rel="stylesheet" href="media/layout.css" />
	    <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
         <script src="js/script/script-all.min.js" type="text/javascript"></script>

</head>
<body>
        <div id="header">
			<div class="bg-help">
				<div class="inBox">
					<h1 id="logo"><a href='#'>Event Calendar</a></h1>
					<form>
					<div class="search-container">
                      <form action="/action_page.php">
                       <input type="text" placeholder="Search.." name="search">
                          <button type="submit">Submit</button>
					</form>
					<hr class="hidden" />
				</div>
			</div>
        </div>
        <div class="shadow"></div>
        <div class="hideSkipLink">
        </div>
        <div class="main">

            <div style="float:left; width: 160px;">
                <div id="nav"></div>
            </div>
            <div style="margin-left: 160px;">

                

                <div id="dp"></div>
            </div>

            <script type="text/javascript">

                var nav = new DayPilot.Navigator("nav");
                nav.showMonths = 3;
                nav.skipMonths = 3;
                nav.selectMode = "week";
                nav.onTimeRangeSelected = function(args) {
                    dp.startDate = args.day;
                    dp.update();
                    loadEvents();
                };
                nav.init();

                var dp = new DayPilot.Calendar("dp");
                dp.viewType = "Week";

                dp.eventDeleteHandling = "Update";

                dp.onEventDeleted = function(args) {
                    $.post("backend_delete.php",
                        {
                            id: args.e.id()
                        },
                        function() {
                            console.log("Deleted.");
                        });
                };

                dp.onEventMoved = function(args) {
                    $.post("backend_move.php",
                            {
                                id: args.e.id(),
                                newStart: args.newStart.toString(),
                                newEnd: args.newEnd.toString()
                            },
                            function() {
                                console.log("Moved.");
                            });
                };

                dp.onEventResized = function(args) {
                    $.post("backend_resize.php",
                            {
                                id: args.e.id(),
                                newStart: args.newStart.toString(),
                                newEnd: args.newEnd.toString()
                            },
                            function() {
                                console.log("Resized.");
                            });
                };

                // event creating
                dp.onTimeRangeSelected = function(args) {
                    var name = prompt("New event name:", "Event" );
					var description = prompt("Description:", "Event Description" );
                        alert(name + "\n" + description);
                    dp.clearSelection();
                    if (!name) return;
                    var e = new DayPilot.Event({
                        start: args.start,
                        end: args.end,
                        id: DayPilot.guid(),
                        resource: args.resource,
                        text: name
                    });
                    dp.events.add(e);

                    $.post("backend_create.php",
                            {
                                start: args.start.toString(),
                                end: args.end.toString(),
                                name: name
                            },
                            function() {
                                console.log("Created.");
                            });

                };

                dp.onEventClick = function(args) {
                    alert("clicked: " + args.e.id());
                };

                dp.init();

                loadEvents();

                function loadEvents() {
                    dp.events.load("backend_events.php");
                }

            </script>

            <script type="text/javascript">
            $(document).ready(function() {
                $("#theme").change(function(e) {
                    dp.theme = this.value;
                    dp.update();
                });
            });
            </script>

        </div>
        <div class="clear">
        </div>

</body>
</html>

