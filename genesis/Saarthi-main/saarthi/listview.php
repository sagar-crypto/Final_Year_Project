<!DOCTYPE html>
<html>
<head>
  <title>SAARTHI</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">SAARTHI</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Items
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Contact</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

<!-- <input type="text" id="cropname" /> -->
<!-- <input type="button" id="getUser" onclick="execute()" value="Get Details"/> -->

<div class="container my-4">
        <h2 class="text-center">SEARCH CROPS NEARBY YOU</h2>
            <form action="listnew.php" method="get">
            <div class="form-group">
                <label for="title">Search by crop name : </label>
                <input type="text" class="form-control" id="cropname" name="cname" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">Enter crop name : </small>
            </div>

            <div class="form-group" style="display: none;">
                <label for="description">Search by location : </label>
                <input type="text" class="form-control" id="location">
                <small id="emailHelp" class="form-text text-muted">Enter Location : </small>
            </div>

            <input type="button" id="getUser" onclick="execute()" class="btn btn-primary" value="Search">
            <!-- <button  id="clear" class="btn btn-primary" onclick="clearStorage()">Clear list</button> -->
            </form>

        <div id="items" class="my-4">
            <h2>Your Items</h2>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SNo</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Item Location</th>
                    <th scope="col">Informed by:</th>  
                    <th scope="col">price</th> 
                    <th scope="col">Actions</th> 
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <tr>
                    <th scope="row">1</th>
                    <td></td>
                    <td></td>
                    <td></td>  
                    <td></td> 
                    <td><button class="btn btn-sm btn-primary">Delete</button></td> 
                  </tr>
                  
                </tbody>
              </table>
        </div>
    </div>
</body>
</html>

<script>
var x=1;
var ids=[];
var names=[];
var cnames=[];
var locations=[];
var prices=[];
while( x<16){
        //var user_id = $('#user_id').val();
        var user_id = x;
        $.ajax({
            type:'POST',
            url:'getData.php',
            dataType: "json",
            data:{userid:user_id,},
            success:function(data){
                if(data.status == 'ok'){
                    ids.push(data.result.id);
                    names.push(data.result.name);
                    cnames.push(data.result.cname);
                    locations.push(data.result.location);
                    prices.push(data.result.price);
                    $('.user-content').slideDown();
                }else{
                    $('.user-content').slideUp();
                    alert("User not found...");
                } 
            }
        });
    x++;
}
var cropname;
//$("#getUser").on('click',function(){
//    cropname= $("#cropname").val();
//});



function output(){
    console.log(ids);
    console.log(names);
    console.log(cnames);
    console.log(locations);
    console.log(prices);
}

function execute(){
        console.log('executing')
        clearStorage();
        searchstore();
        //storedata();
        showdata();
    }
    
    function clearStorage(){    
                console.log('Clearing the storage')
                localStorage.clear();    
            }
    function storedata(){
                
                console.log("Updating List...");
                var i=0;
                while( i < ids.length) {
                console.log(ids[i]);
                console.log(names[i]);
                console.log(cnames[i]);
                console.log(locations[i]);
                console.log(prices[i]);
                if (localStorage.getItem('itemsJson')==null){
                    itemJsonArray = [];
                    itemJsonArray.push([ids[i],names[i], cnames[i] ,locations[i], prices[i]]);
                    localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray))
                }
                else{
                    itemJsonArrayStr = localStorage.getItem('itemsJson')
                    itemJsonArray = JSON.parse(itemJsonArrayStr);
                    itemJsonArray.push([ids[i],names[i], cnames[i] ,locations[i], prices[i]]);
                    localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray))
                }
                i++;
            }
            }

    function searchstore(){
                
                console.log("Updating List...");
                var i=0;
                cropname= $("#cropname").val();
                console.log(cropname);
                while( i < ids.length){
                    if( cnames[i]==cropname){
                        //console.log(ids[i]);
                        //console.log(names[i]);
                        //console.log(cnames[i]);
                        //console.log(locations[i]);
                        //console.log(prices[i]);
                        if (localStorage.getItem('itemsJson')==null){
                            itemJsonArray = [];
                            itemJsonArray.push([ids[i],names[i], cnames[i] ,locations[i], prices[i]]);
                            localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray))
                        }
                        else{
                            itemJsonArrayStr = localStorage.getItem('itemsJson')
                            itemJsonArray = JSON.parse(itemJsonArrayStr);
                            itemJsonArray.push([ids[i],names[i], cnames[i] ,locations[i], prices[i]]);
                            localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray))
                        }
                    }
                i++;
            }
            }


    function showdata(){
        console.log("showing data");
        if (localStorage.getItem('itemsJson')==null){
            itemJsonArray = []; 
            localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray))
        } 
        else{
            itemJsonArrayStr = localStorage.getItem('itemsJson')
            itemJsonArray = JSON.parse(itemJsonArrayStr); 
        }
        // Populate the table
        let tableBody = document.getElementById("tableBody");
        let str = "";
        itemJsonArray.forEach((element, index) => {
            str += `
            <tr>
            <th scope="row">${index + 1}</th>
            <td>${element[2]}</td>
            <td>${element[3]}</td>
            <td>${element[1]}</td>
            <td>${element[4]}</td>
            <td><button class="btn btn-sm btn-primary" onclick="deleted(${index})">Delete</button></td> 
            </tr>`; 
        });
        tableBody.innerHTML = str;
    }
    
    function deleted(itemIndex){
        console.log("Delete", itemIndex);
        itemJsonArrayStr = localStorage.getItem('itemsJson')
        itemJsonArray = JSON.parse(itemJsonArrayStr);
        // Delete itemIndex element from the array
        itemJsonArray.splice(itemIndex, 1);
        localStorage.setItem('itemsJson', JSON.stringify(itemJsonArray));
        showdata();

    }

</script>