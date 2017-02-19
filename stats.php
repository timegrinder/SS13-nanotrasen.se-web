<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paradise Station</title>
    <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/jquery.jqplot.min.css">
    <link href="https://fonts.googleapis.com/css?family=Exo" rel="stylesheet">
    <link rel="icon" href="Images/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/jquery.jqplot.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.highlighter.min.js"></script>
    <script type="text/javascript" language="javascript">
        var phpdate = <?php
        echo date("'Y-m-d'");
        ?>;
        var dateFormat = "yy-mm-dd";
        var startDate = phpdate;
        var endDate = phpdate;
        var datasets = ['player_count', 'admin_count'];
        var allDatasets = ['player_count', 'new_player_count', 'admin_count', 'ghost_count', 'cpu', 'power_generated'];
        var plot;
        $(document).ready(function() {
            plot = $.jqplot('plot', [[null]], {
                axes: {
                    xaxis: {
                        renderer:$.jqplot.DateAxisRenderer,
                        min: (phpdate + ' 00:00:00'),
                        max: (phpdate + ' 23:59:59'),
                        tickOptions: {
                            formatString:"%F %R"
                        }
                    }
                },
                legend: {
                    show: false,
                    placement: 'inside'
                },
                highlighter: {
                    show: true
                },
                seriesDefaults: {
                    rendererOptions: {
                        smooth: true
                    },
                    showMarker:false
                }
            });
            
            var from = $('#startDate');
            var to = $('#endDate');
            from.val(startDate);
            to.val(endDate);
            from.datepicker();
            from.datepicker("option", "dateFormat", dateFormat);
            from.datepicker("option", "maxDate", $.datepicker.parseDate(dateFormat, endDate));
            from.on("change", function() {
                to.datepicker("option", "minDate", $.datepicker.parseDate(dateFormat, this.value));
                startDate = this.value;
                updatePlot();
            });
            to.datepicker();
            to.datepicker("option", "dateFormat", dateFormat);
            to.datepicker("option", "minDate", $.datepicker.parseDate(dateFormat, startDate));
            to.datepicker("option", "maxDate", $.datepicker.parseDate(dateFormat, phpdate));
            to.on("change", function() {
                from.datepicker("option", "maxDate", $.datepicker.parseDate(dateFormat, this.value));
                endDate = this.value;
                updatePlot();
            });

            from.val(startDate);
            to.val(endDate);
            updatePlot();
        });
        function updatePlot() {
            $.ajax({
                "url":"line-plot-data.php",
                "data":{"startDate":startDate,"endDate":endDate},
                "success":function(data) {
                    plot.replot(JSON.parse(data));
                }
            });
        }

    </script>
    
</head>
<body>
    <div class="wrapper">
    <nav>
       <ul>
            <li id ="logo">  <a href="#"><img src="Images/Paradise2icon.PNG" alt="server logo"></a></li>
            <li id="server"> <a href="byond://nanotrasen.se:6666">Server</a></li>
            <li id="patreon"><a href="https://www.patreon.com/ParadiseStation">Patreon</a></li>
            <li id="wiki">   <a href="https://nanotrasen.se/wiki/index.php/Main_Page">Wiki</a></li>
            <li id="forums"> <a href="https://nanotrasen.se/phpBB3/">Forums</a></li>
            <li id="discord"><a href="https://discord.gg/nuqD478">Discord</a></li>
            <li id="github"> <a href="https://github.com/ParadiseSS13/Paradise">GitHub</a></li>
        </ul>
    </nav>
    <main>
        <div id='content'>
            <fieldset>
                <legend>Select a date range</legend>
                From <input type="text" id="startDate" name="startDate">
                To <input type="text" id="endDate" name="endDate">
            </fieldset>
            <div id='plot' style='height:600px'></div>
        </div>
    </main>
    </div>
    <footer><p>This webpage is licensed under the MIT License (MIT).</p></footer>
</body>
</html>