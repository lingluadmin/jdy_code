$(document).ready(function(){


    // === Prepare the chart data ===/

    var newsdata = [];
    var nums = 1;
    var newsticks = [];
    console.log( ticks);
    $.each(ticks, function(t, tick){
            newsticks.push([t, tick]);
    });

    $.each(chartData, function(i, item){

        $.each(item, function(j, data) {

            classId = 'chart'+nums;
            $.each(data, function(k, row) {
               newsdata.push([k, row]);
            });


            nums++;
            getChart(classId,newsdata,newsticks,j);
            newsdata = [];
        });

    });


    function getChart(classId,chartData,newsticks,chartlable) {

            if($("#"+classId).length) {
                // === Make chart === //
                var plot = $.plot($("#"+classId),
                    [ { data: chartData, label: chartlable, color: "#ee7951" } ], {
                        series: {
                            lines: { show: true , lineWidth: 1},
                            points: { show: true }
                        },
                        legend: {
                            position: "se"
                        },
                        grid: { hoverable: true, clickable: true },

                        xaxis: { ticks: newsticks},

                    });


                // === Point hover in chart === //
                var previousPoint = null;
                $("#"+classId).bind("plothover", function (event, pos, item) {

                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $('#tooltip').fadeOut(200,function(){
                                $(this).remove();
                            });
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            maruti.flot_tooltip(item.pageX, item.pageY,item.series.label + " of " + x + " = " + y);
                        }

                    } else {
                        $('#tooltip').fadeOut(200,function(){
                            $(this).remove();
                        });
                        previousPoint = null;
                    }
                });

            }

    }




});

/*maruti = {
    // === Peity charts === //
    peity: function(){
        $.fn.peity.defaults.line = {
            strokeWidth: 1,
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $.fn.peity.defaults.bar = {
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $(".peity_line_good span").peity("line", {
            colour: "#57a532",
            strokeColour: "#459D1C"
        });
        $(".peity_line_bad span").peity("line", {
            colour: "#FFC4C7",
            strokeColour: "#BA1E20"
        });
        $(".peity_line_neutral span").peity("line", {
            colour: "#CCCCCC",
            strokeColour: "#757575"
        });
        $(".peity_bar_good span").peity("bar", {
            colour: "#459D1C"
        });
        $(".peity_bar_bad span").peity("bar", {
            colour: "#BA1E20"
        });
        $(".peity_bar_neutral span").peity("bar", {
            colour: "#4fb9f0"
        });
    },

    // === Tooltip for flot charts === //
    flot_tooltip: function(x, y, contents) {

        $('<div id="tooltip">' + contents + '</div>').css( {
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn(200);
    }
}*/



