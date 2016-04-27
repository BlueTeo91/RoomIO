// * Name: main.js
// * Project: Footfall
// * Author: David Haylock & Oliver Humpage
// * Creation Date: 20-11-2014
// * Copyright: (c) 2015 by Watershed Arts Trust Ltd.
/*var totalsData = {};*/
var totalChart;
var interval = 300;
var events;
var labelLength = 0;

//--------------------------------------------------------------
// *
// *	Fill the chart with existing data
// *
//--------------------------------------------------------------

//TODO: LABELS OPPDATERER SEG IKKE AV SEG SELV SELV OM VERDIENE BLIR SATT INN!!!
function preLoadData()
{
	// Get the First Load of Data
	$.ajax({
		url:"http://www.matteoconti91.joomlafree.it/upload.php?get&interval="+interval,
		async: true,
		dataType: 'json',
		type:'get',
	}).done(function(data){

		// If we already have data update the labels on the web page
		updateLabels(data);
		for (var i in data) {
			labelLength++;
			var label = data[i].timekey.substring(11,16);
			var eventsDatasets = [];
			eventsDatasets.push(data[i].total);
			eventCount = 0;
			for (var title in events) {
				eventAtCurrentLabel = null;
				var ev = events[title];
				//console.log(title);
				for (var t in ev) {
					if (ev[t].start <= label && ev[t].end >= label) {
						//console.log(title+" in label "+label);
						eventAtCurrentLabel = -10-(eventCount*10);
					}
				}
				eventsDatasets.push(eventAtCurrentLabel);
				eventCount++;
			}
			totalChart.addData(eventsDatasets,label);
		}
	});
}
//--------------------------------------------------------------
// *
// *	Create Total Chart
// *
//--------------------------------------------------------------
function createTotalChart()
{
	var canvas = document.getElementById('totalChart'),
	ctx = canvas.getContext('2d'),
		totalsData = {
			labels: [],
			datasets: [
		{
			label: "People In Room",
			fillColor: "rgba(0,77,255,.01)",
			strokeColor: "rgba(0,77,255,1)",
			pointColor: "rgba(0,77,255,1)",
			pointStrokeColor: "#fff",
			data: []
		},
		{
			fillColor: "rgba(0,77,255,.01)",
			strokeColor: "rgba(151,187,205,1)",
			pointColor: "rgba(0,77,255,.01)",
			pointStrokeColor: "rgba(0,77,255,.01)",
			strokeWidth: 5,
			data: []
		}
		]
	},
	latestLabel = totalsData.labels[6];
    totalChart = new Chart(ctx).Line(totalsData, {animationSteps:15});
	preLoadData();
}
//--------------------------------------------------------------
// *
// *	Function to Update Labels
// *
//--------------------------------------------------------------
function updateLabels(data)
{
	document.getElementById("currentNumber").innerHTML = data[data.length-1].total;
	/*document.getElementById("currentNumber").innerHTML = data[data.length-1];*/
	document.getElementById("totalIn").innerHTML = data[data.length-1].totalin;
}
//--------------------------------------------------------------
// *
// *	Function to Update End Value of Totals Chart
// *
//--------------------------------------------------------------
function updateTotals(data)
{
	var totalDataLength = totalChart.datasets[0].points.length-1;
	totalChart.datasets[0].points[totalDataLength].value = data[data.length-1].total;
	if (totalDataLength-1 > labelLength) {
		var label = totalChart.datasets[0].points[totalDataLength].label;
		var eventCount = 0;
		for (var title in events) {
			eventAtCurrentLabel = null;
			var ev = events[title];
			for (var t in ev) {
				if (ev[t].start <= label && ev[t].end >= label) {
					eventAtCurrentLabel = -10-(eventCount*10);
				}
			}
			totalChart.datasets[eventCount+1].points[totalDataLength].value = eventAtCurrentLabel;
			eventCount++;
		}
	}
	totalChart.update();
}
//--------------------------------------------------------------
// *
// *	Function which checks for New data
// *
//--------------------------------------------------------------
function updateValues()
{
	$.ajax({
		url:"http://www.matteoconti91.joomlafree.it/upload.php?get&interval="+interval,
		async:true,
		dataType: 'json',
		type:'get',
	}).done(function(data)
	{
		updateLabels(data);
		updateTotals(data);
		addDataToCharts(data);
	});
}
//--------------------------------------------------------------
// *
// *	Function to Add Data to Charts
// *
//--------------------------------------------------------------
function addDataToCharts(data)
{
	// Probably a better way to do this
	if (data[data.length-1].timekey.substring(11,16) == totalsData.labels[totalsData.labels.length-1])
	{
	}
	else
	{
		totalChart.addData([data[data.length-1].total],data[data.length-1].timekey.substring(11,16));
		var totalDataLength = totalChart.datasets[0].points.length-2;
		totalChart.datasets[0].points[totalDataLength].value = data[data.length-2].total;
		totalChart.update();
		totalChart.removeData();
	}
	//FABFIVE: try to update labels automatically when new data arrives.
	//updateLabels(data);
}
//--------------------------------------------------------------
// *
// *	Main
// *
//--------------------------------------------------------------
$(document).ready(function(){
	interval = $('#interval').val();
	$('#interval').change(function(){
		interval = $('#interval').val();
		totalChart.destroy();
		createTotalChart();
		console.log(document.getElementById("test").innerHTML); //Contains the current date selected
	});
	// Functions
	createTotalChart()
	//set how often to look for new movement in milliseconds.
	//could send in the interval to set how often the page should get new values for the graph.
	setInterval(function(){updateValues()},10000);

});