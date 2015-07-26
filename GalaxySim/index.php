<html>
	<head>
	<title>Galaxy Generation</title>
	<script src="https://www.google.com/jsapi"></script>
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<!--   <script src="http://prithwis.x10.bz/charts/jquery.csv-0.71.js"></script> -->
	<script src="https://jquery-csv.googlecode.com/files/jquery.csv-0.71.js"></script>
	<script type='text/javascript'>
	// load the visualization library from Google and set a listener
	google.load("visualization", "1", {packages:["corechart", "scatter"]});
	google.setOnLoadCallback(drawChartfromCSV);
	count = 0;
	countTmp = 0;
	lastFileUpdate=0;
	lastRun = "";
	minMax = 0;
	phpMax = '<?=$_GET["MinMax"] ?>';
	phpTimeout = '<?=$_GET["Timeout"] ?>';
	function Left(str, n){
		if (n <= 0)
		    return "";
		else if (n > String(str).length)
		    return str;
		else
		    return String(str).substring(0,n);
	}
	function Right(str, n){
	    if (n <= 0)
	       return "";
	    else if (n > String(str).length)
	       return str;
	    else {
	       var iLen = String(str).length;
	       return String(str).substring(iLen, iLen - n);
	    }
	}
	if(phpTimeout!=''){
		looper = phpTimeout;
	}else{
		looper = 10000;
	}
	if(phpMax!=''){
		minMax = phpMax;
		defMax = true
	}else{
		defMax = false;
	}
	endVal = '';
	function showEntry(tmpClass){
		try{
			if(document.getElementsByName('chk'+tmpClass)[0].checked){
				return true;
			}else{
				return false;
			}
		}catch(e){return true}
	}
	function solarMass(tmpMass){
		thisClass='';
		if(tmpMass>10000){
			tmpMass = tmpMass / 1988550000000000000000000000000
		}
		if(tmpMass>160){
			thisClass="Z";
		}else if(tmpMass>=16){	
			thisClass="O";
		}else if(tmpMass>=2.1){
			thisClass="B";
		}else if(tmpMass>=1.4){
			thisClass="A";
		}else if(tmpMass>=1.0){
			thisClass="F";
		}else if(tmpMass>=0.8){
			thisClass="G";
		}else if(tmpMass>=0.45){
			thisClass="K";
		}else if(tmpMass>=0.08){
			thisClass="M";
		}
		return thisClass;
	}
   function drawChartfromCSV(){
	 // grab the CSV
	 	galGen='';
	 	if(document.getElementsByName('generation')[0].checked){
	 		galGen = document.getElementsByName('generation')[0].value;
	 	}
		$.get("./simoutput/output.php?generation="+galGen+"&LastUpdate="+lastFileUpdate, function(csvString) {
			if(!defMax){
				minMax = 0;
			}
		 // transform the CSV string into a 2-dimensional array
			var arrayData = $.csv.toArrays(csvString, {onParseValue: $.csv.hooks.castToScalar});
			for(i=0;i<arrayData.length;i++){
				if(arrayData[i]=="END"){
					countTmp = arrayData.length - 1
					endVal = arrayData[countTmp-1];
				}
			}
			lastFileUpdate = Right(arrayData[countTmp], 10);
			if(endVal=="END")
			if((countTmp>(count)) | (lastRun != (arrayData[1]+arrayData[2]+arrayData[3]))){
				lastRun = arrayData[1]+arrayData[2]+arrayData[3]
				counter0=0;
				counter1=0;
				counter2=0;
				counter3=0;
				counter4=0;
				counter5=0;
				counter6=0;
				counter7=0;
				counter8=0;
				for(i=1;i<arrayData.length-1;i++){
					thisVal = arrayData[i]+""
					temp = thisVal.split(",")
			   		tmpClass="";
					num0 = parseFloat(temp[0]);
					num1 = parseFloat(temp[1]);
					if(!defMax){
						if(num0<0){t1=num0*-1;}else{t1=num0;}
						if(minMax<t1){minMax=t1;}
						if(num1<0){t1=num1*-1;}else{t1=num1;}
						if(minMax<t1){minMax=t1;}
					}
			   		if(minMax>0 & ((num0>minMax) | (num0<(minMax*-1)) | (num1>minMax) | (num1<(minMax*-1)))){
			   				
		   			}else{
		   				try{
							tmpMass = parseFloat(temp[2].trim());
							tmpClass = solarMass(tmpMass)
		   				}catch(e){}
		   				if(tmpClass==''){try{tmpClass = temp[3].trim();}catch(e){}}
						if(tmpClass=='M'){
							counter7++;
						}else if(tmpClass=='K'){
							counter6++;
						}else if(tmpClass=='G'){
							counter5++;
						}else if(tmpClass=='F'){
							counter4++;
						}else if(tmpClass=='A'){
							counter3++;
						}else if(tmpClass=='B'){
							counter2++;
						}else if(tmpClass=='O'){
							counter1++;
						}else if(tmpClass=='Z'){
							counter8++;
						}else{
							counter0++;
						}
		   			}
				}
				var data = new google.visualization.DataTable();
				data.addColumn('number', 'X');
				data.addColumn('number', 'Class O ('+counter1+')');
				data.addColumn('number', 'Class B ('+counter2+')');
				data.addColumn('number', 'Class A ('+counter3+')');
				data.addColumn('number', 'Class F ('+counter4+')');
				data.addColumn('number', 'Class G ('+counter5+')');
				data.addColumn('number', 'Class K ('+counter6+')');
				data.addColumn('number', 'Class M ('+counter7+')');
				data.addColumn('number', 'Black Hole ('+counter8+')');
				data.addColumn('number', 'Class ? ('+counter0+')');
				for(i=1;i<arrayData.length-1;i++){
					thisVal = arrayData[i]+""
					temp = thisVal.split(",")
					num0 = parseFloat(temp[0]);
					num1 = parseFloat(temp[1]);
			   		if(minMax>0 & ((num0>minMax) | (num0<(minMax*-1)) | (num1>minMax) | (num1<(minMax*-1)))){

		   			}else{
				   		tmpClass="";
		   				try{
							tmpMass = parseFloat(temp[2].trim());
							//console.log(temp[3])
							tmpClass = solarMass(tmpMass)
		   				}catch(e){}
		   				if(tmpClass==''){try{tmpClass = temp[3].trim();}catch(e){}}
						if(tmpClass=='Z'){
							data.addRows([[num0, null, null, null, null, null, null, null, num1, null]]);
						}else if(tmpClass=='M'){
							if(showEntry(tmpClass))data.addRows([[num0, null, null, null, null, null, null, num1, null, null]]);
						}else if(tmpClass=='K'){
							if(showEntry(tmpClass))data.addRows([[num0, null, null, null, null, null, num1, null, null, null]]);
						}else if(tmpClass=='G'){
							if(showEntry(tmpClass))data.addRows([[num0, null, null, null, null, num1, null, null, null, null]]);
						}else if(tmpClass=='F'){
							if(showEntry(tmpClass))data.addRows([[num0, null, null, null, num1, null, null, null, null, null]]);
						}else if(tmpClass=='A'){
							if(showEntry(tmpClass))data.addRows([[num0, null, null, num1, null, null, null, null, null, null]]);
						}else if(tmpClass=='B'){
							if(showEntry(tmpClass))data.addRows([[num0, null, num1, null, null, null, null, null, null, null]]);
						}else if(tmpClass=='O'){
							if(showEntry(tmpClass))data.addRows([[num0, num1, null, null, null, null, null, null, null, null]]);
						}else{
							data.addRows([[num0, null, null, null, null, null, null, null, null, num1]]);
						}
		   			}
				}
				var optionsScatter = {
					title: 'Galaxy Map ' + countTmp,
					pointSize: 1,
					hAxis: {viewWindow: {max: minMax, min: (minMax*-1)}, minValue: (minMax*-1), maxValue: (minMax)},
					vAxis: {viewWindow: {max: minMax, min: (minMax*-1)}, minValue: (minMax*-1), maxValue: (minMax)},
					height:(window.innerHeight - 50),
					width: (window.innerWidth - 50), 
					series: {
						0:{pointSize: 6.6},
						1:{pointSize: 4.2},
						2:{pointSize: 1.8},
						3:{pointSize: 1.4},
						4:{pointSize: 1.15},
						5:{pointSize: 0.96},
						6:{pointSize: 0.7},
						7:{pointSize: 10},
						8:{pointSize: 0.1}
					}
				};
				var chart = new google.visualization.ScatterChart(document.getElementById('csv2chart'));
				chart.draw(data, optionsScatter);
				document.getElementById('csv2chart').style.width = (window.innerWidth - 50)+'px';
				document.getElementById('csv2chart').style.height = (window.innerHeight - 50)+'px';
				document.getElementById('LastUpdate').innerHTML = 'LastRun: '+Date()
				count = countTmp;
			}else if(countTmp<100){
				if(countTmp>10){
					count=0;
					countTmp=0;
				}
			}
		 });
		 try{
		 	if(endVal=="END"){
			 	setTimeout("drawChartfromCSV()", looper);
				document.getElementById('LastUpdate').innerHTML = 'LastRun: '+Date()
		 	}else{
			 	setTimeout("drawChartfromCSV()", 100);
				document.getElementById('LastUpdate').innerHTML = 'Checking 1: '+Date() + " "  + countTmp + endVal
		 	}
		 }catch(e){
		 	setTimeout("drawChartfromCSV()", 100);
			document.getElementById('LastUpdate').innerHTML = 'Checking 2: '+Date() + " " + countTmp + endVal
		 }
	   }
	</script>
</head>
<body>
	<input type="checkbox" name="generation" value="testing"><label for='generation'>Galaxy Generation</label>
	<input type="checkbox" name="chkO" value="1" checked=checked><label for='chkO'>O</label>
	<input type="checkbox" name="chkB" value="1" checked=checked><label for='chkB'>B</label>
	<input type="checkbox" name="chkA" value="1" checked=checked><label for='chkA'>A</label>
	<input type="checkbox" name="chkF" value="1" checked=checked><label for='chkF'>F</label>
	<input type="checkbox" name="chkG" value="1" checked=checked><label for='chkG'>G</label>
	<input type="checkbox" name="chkK" value="1" checked=checked><label for='chkK'>K</label>
	<input type="checkbox" name="chkM" value="1" checked=checked><label for='chkM'>M</label>
	<br /><span id='LastUpdate'>Date</span>
	<div id='csv2chart'> </div>
</body>
</html>
