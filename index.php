<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pokedex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <!--<div class="row align-items-center">
        <div class="col-12 text-center">
            <img src="images/banner.jpg" alt="" height="400" class="d-none d-md-inline">
        </div>
    </div>-->
    <div class="row align-items-center" id="searchInputRow">
        <div class="col-12 col-md-4 offset-md-4 text-center" id="searchInputCol">
            <form action="index.php" method="post">
                <label for="pokeName" id="searchLabel">Enter the Pokemon name or ID and press the search button</label>
                <input type="text" name="pokeName" id="pokeName">
                <br>
                <button type="submit" class="btn" id="submit" name="submit">Search</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
<?php
function displayPokemon($src , $name, $id, $moves ){
    echo " <div class='container'>";
    echo " <div class='row pokeDetails align-items-center'>";
    echo "<div class='col-12 col-md-6 text-center'>";
    echo "<img src='{$src}' class='pokeImg' height='250'>";
    echo "</div> ";
    echo "<div class='row col-12 col-md-6 details text-center'>";
    echo "<h3>$name</h3>";
    echo "<h4>$id</h4>";
    for ($x = 0 ; $x < count($moves); $x ++){
        echo "<p>$moves[$x]</p>";
    }
    // close row and col
    echo "</div></div>";
}
function displayEvo($names , $images){
    $count = count($names);
    echo "<div class='row pokeDetails'>";
    for ($x = 0 ; $x < $count ; $x++){
        echo "<div class='col-12 col-md-4 text-center'>";
        echo "<h4>{$names[$x]}</h4>";
        echo "<img src='{$images[$x]}' class='pokeImg' height='250'>";
        echo "</div>";
    }
    /// for row
    echo "</div>";
    /// for container
    echo "</div>";
}
function displayNoEvo(){

    echo "<div class='row pokeDetails'>";
    echo "<div class='col-12 col-md-4 text-center offset-md-4'>";
    echo "<p>This pokemon has no evolutions!</p>";
    //for row and col
    echo "</div></div>";
    // for container
    echo "</div>";
}
function findEvo($chain){
   $secondEvo = count($chain["evolves_to"]);
   $evoImgArr = [];
   $evoNameArr = [];
   if ($secondEvo == 0 ){
      displayNoEvo();
   }else{
       $first = toGetFileFromApi("https://pokeapi.co/api/v2/pokemon/{$chain['species']['name']}");
       $evoImgArr = pushImgSrc($evoImgArr , $first);
       $evoNameArr = pushName($evoNameArr , $first);
       for ($x = 0 ; $x < $secondEvo; $x++){
            $second = toGetFileFromApi("https://pokeapi.co/api/v2/pokemon/{$chain['evolves_to'][$x]['species']['name']}");
            $evoImgArr = pushImgSrc($evoImgArr , $second);
            $evoNameArr = pushName($evoNameArr, $second);
            $thirdEvo = count($chain['evolves_to'][$x]['evolves_to']);
            if ($thirdEvo != 0){
                for ($y = 0 ; $y < $thirdEvo; $y ++){
                    $third = toGetFileFromApi("https://pokeapi.co/api/v2/pokemon/{$chain['evolves_to'][$x]['evolves_to'][$y]['species']['name']}");
                    $evoImgArr = pushImgSrc($evoImgArr , $third);
                    $evoNameArr = pushName($evoNameArr, $third);
                }
            }
       }
       displayEvo($evoNameArr,$evoImgArr);
   }
}
function pushImgSrc($arr , $poke){
    array_push($arr , $poke["sprites"]["other"]["home"]["front_default"]);
    return $arr;
}
function pushName ($arr , $poke){
    array_push($arr, $poke["name"]);
    return $arr;
}

function getThePokemonInfo($obj){
    $src = $obj["sprites"]["other"]["home"]["front_default"];
    $name = $obj["name"];
    $id = $obj["id"];
    $movesArr = [];
    for ($x=0 ; $x <4 ; $x ++){
        array_push($movesArr , $obj["moves"][$x]["move"]["name"]);
    }
    displayPokemon( $src, $name, $id, $movesArr);
}
function toGetFileFromApi ($url){
    $jasonFile = file_get_contents($url);
    $obj = json_decode($jasonFile,true);
    return $obj;
}
if (isset($_POST['submit'])){
    $pokemon = toGetFileFromApi("https://pokeapi.co/api/v2/pokemon/".$_POST['pokeName']);
    getThePokemonInfo($pokemon);
    $species = toGetFileFromApi($pokemon["species"]["url"]);
    $evoObj = toGetFileFromApi($species["evolution_chain"]["url"]);
    findEvo($evoObj["chain"]);
}
?>