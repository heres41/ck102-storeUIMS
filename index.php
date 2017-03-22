<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?php
$services = getenv("VCAP_SERVICES");
$services_json = json_decode($services, true);

for ($i = 0; $i < sizeof($services_json["user-provided"]); $i++){
	if ($services_json["user-provided"][$i]["name"] == "ck102-CatalogMS-API"){
		$catalogHost = $services_json["user-provided"][$i]["credentials"]["host"];
	}
}

$parsedURL = parse_url($catalogHost);
$catalogRoute = $parsedURL["scheme"] . "://" . $parsedURL["host"];

function CallAPI($method, $url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
$result = CallApi("GET", $catalogRoute . "/items");
?>

<script>
var items = <?php echo $result?>;
  
function loadItems(){
	var i = 0;
	console.log("Load Items: " + items.rows);
	document.getElementById("loading").innerHTML = "";
	for(i = 0; i < items.rows.length; ++i){
		addItem(items.rows[i].doc);
	}
}

function addItem(item){
	var div = document.createElement('div');
	div.className = 'row';
	div.innerHTML = //"<div class ='well'><img width='100%' height='auto' src = '"+item.imgsrc+"'/><br><button onclick='orderItem(\""+item._id+"\")'><b>Buy</b></button><br><u>"+item.name+"</u><br>"+item.description+"<br><b>$"+item.usaDollarPrice + "</b></div>";
	
	//<div class='row'>
          "  <div class='box'>"
           +"     <div class='col-lg-12'>"
            +"        <hr>"
            + "       <h2 class='intro-text text-center'>"+item.name
            +  "      </h2>"
            +   "     <hr>"
            +    "</div>"
            +    "<div class='col-md-6'>"
             +   "    <img class='img-responsive img-border-left' src = '"+item.imgsrc+"' alt=''>"
              +  "</div>"
             +   "<div class='col-md-6'>"
              +  "    <p>"+item.description+"</p>"
              +  "    <p><strong>"+item.usaDollarPrice+"</strong></p>"
              +  "    <br><button onclick='orderItem(\""+item._id+"\")'><b>Buy</b></button>"
			+"		</div>"
           +  "   <div class='clearfix'></div>"
         +   "</div>";

        //</div>
	
	
	document.getElementById('boxes').appendChild(div);
}

function orderItem(itemID){
	//create a random customer ID and count
	var custID = Math.floor((Math.random() * 999) + 1); 
	var count = Math.floor((Math.random() * 9999) + 1); 
	var myjson = {"itemid": itemID, "customerid":custID, "count":count};
    
    $.ajax ({
    	type: "POST",
    	contentType: "application/json",
	    url: "submitOrders.php",
	    data: JSON.stringify(myjson),
	    dataType: "json",
	    success: function( result ) {
	        if(result.httpCode != "201" && result.httpCode != "200"){
	        	alert("Failure: check that your JavaOrders API App is running and your user-provided service has the correct URL.");
	        }
	        else{
	        	alert("Order Submitted! Check your Java Orders API to see your orders: \n" + result.ordersURL);
	        }
	    },
	    error: function(XMLHttpRequest, textStatus, errorThrown) { 
	    	alert("Error");
        	console.log("Status: " , textStatus); console.log("Error: " , errorThrown); 
    }  
	});

}

</script>




<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<!--link rel="stylesheet" href="style.css"-->


	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Melvin Berena">

    <title>CK102 Store</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/business-casual.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<!--table class="headerTable">
	<tr>
		<td><span class="pageTitle"><h1>Welcome to the Online Store!</h1></span></td> 
	</tr>
</table-->
<body  onload="loadItems()">
 <div class="brand">CK102 Store</div>
    <div class="address-bar">2 Bloor Street West, Toronto | 416 123 1234</div>

    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand" href="index.html">CK102 Store</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <!--div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="about.html">About</a>
                    </li>
                    <li>
                        <a href="blog.html">Blog</a>
                    </li>
                    <li>
                        <a href="contact.html">Contact</a>
                    </li>
                </ul>
            </div-->
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

	<div id="loading"><br>Loading...</div>
    <div class="container" id='boxes'>

		<div id='boxes' class="notes"></div>
	
    </div>
    <!-- /.container -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Copyright &copy; CK102 Store</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>









	
</body>
</html>