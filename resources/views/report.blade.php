<!DOCTYPE html>
<meta charset="utf-8">
<body>
<link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
<script src="https://d3js.org/d3.v4.min.js"></script>
<div class="svg-div">
<div id="chart1"><strong>Event By Year</strong></div>
<div id="chart2" class='pie_chart'><strong>Group Count By Type</strong></div>
</div>
<script>

    var svg = d3.select('#chart1').append("svg").attr('width', 800).attr('height', 600),
        margin = 200,
        width = +svg.attr("width") - margin,
        height = +svg.attr("height") - margin; 
   
    svg.append("text")
       .attr("transform", "translate(100,0)")
       .attr("x", 50)
       .attr("y", 50)
       .attr("font-size", "20px")
   
       var x = d3.scaleBand().range([0, width]).padding(0.4),
        y = d3.scaleLinear().range([height, 0]);

    var g = svg.append("g")
            .attr("transform", "translate(" + 100 + "," + 100 + ")");

    d3.json("/events_datafile.json", function(error, data) {
        if (error) {
            throw error;
        }

        x.domain(data.map(function(d) { return d.date; }));
        y.domain([0, d3.max(data, function(d) { return d.count_row; })]);

        g.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x))
        .append("text")
        .attr("y", height - 250)
        .attr("x", width - 100)
        .attr("text-anchor", "end")
        .attr("stroke", "black")
         .text("Year");

        g.append("g")
         .call(d3.axisLeft(y).tickFormat(function(d){
             return d;
         }).ticks(10))
         .append("text")
         .attr("transform", "rotate(-90)")
         .attr("y", 6)
         .attr("dy", "-5.1em")
         .attr("text-anchor", "end")
         .attr("stroke", "black")
         .text("Events");

        g.selectAll(".bar")
         .data(data)
         .enter().append("rect")
         .attr("class", "bar")
         .on("mouseover", onMouseOver) //Add listener for the mouseover event
         .on("mouseout", onMouseOut)   //Add listener for the mouseout event
         .attr("x", function(d) { return x(d.date); })
         .attr("y", function(d) { return y(d.count_row); })
         .attr("width", x.bandwidth())
         .transition()
         .ease(d3.easeLinear)
         .duration(400)
         .delay(function (d, i) {
             return i * 50;
         })
         .attr("height", function(d) { return height - y(d.count_row); });
    });
    
    //mouseover event handler function
    function onMouseOver(d, i) {
        d3.select(this).attr('class', 'highlight');
        d3.select(this)
          .transition()     // adds animation
          .duration(400)
          .attr('width', x.bandwidth() + 5)
          .attr("y", function(d) { return y(d.count_row) - 10; })
          .attr("height", function(d) { return height - y(d.count_row) + 10; });

        g.append("text")
         .attr('class', 'val') 
         .attr('x', function() {
             return x(d.date);
         })
         .attr('y', function() {
             return y(d.count_row) - 15;
         })
         .text(function() {
             return [ d.count_row];  // Value of the text
         });
    }

    //mouseout event handler function
    function onMouseOut(d, i) {
        // use the text label class to remove label on mouseout
        d3.select(this).attr('class', 'bar');
        d3.select(this)
          .transition()     // adds animation
          .duration(400)
          .attr('width', x.bandwidth())
          .attr("y", function(d) { return y(d.count_row); })
          .attr("height", function(d) { return height - y(d.count_row); });

        d3.selectAll('.val')
          .remove()
    }

    
    var svg2 = d3.select('#chart2').append("svg").attr('width', 800).attr('height', 550),
            margin = {top: 50, right: 20, bottom: 80, left: 40},
            width = +svg2.attr("width") - margin.left - margin.right,
            height = +svg2.attr("height") - margin.top - margin.bottom,
            radius = Math.min(width, height) / 2;

    var g1 = svg2.append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    svg2.append("g")
    .attr("transform", "translate(" + (width / 2 - 120) + "," + 20 + ")")
    .attr("class", "title")
    
    var color = d3.scaleOrdinal([
        'gray', 'green', 'brown', 'orange', 'yellow',
        'red', 'purple','blue','gold','purple','tan',
        'violet','magenta','arctic','mint','pine'
    ]);

    var pie = d3.pie().value(function (d) {
        return d.total;
    });

    var path = d3.arc()
        .outerRadius(radius - 10).innerRadius(0);

    var label = d3.arc()
        .outerRadius(radius).innerRadius(radius - 80);

    d3.json("/groups_datafile.json", function (error, data) {
        if (error) {
            throw error;
        }
        var arc = d3.arc()
        .outerRadius(radius)
        .innerRadius(0);
        
        var label = d3.arc()
        .outerRadius(radius)
        .innerRadius(radius - 80);

        var arc = g1.selectAll(".arc")
            .data(pie(data))
            .enter()
            .append("g")
            .attr("class", "arc");

        arc.append("path")
            .attr("d", path)
            .attr("fill", function (d) {
                return color(d.data.total);
            });
            
        arc.append("text").attr("transform", function (d) {
                return "translate(" + label.centroid(d) + ")";
            })

            .text(function (d) {
                return d.data.type + d.data.total;
            });
    });

    </script>
</body>
</html>
