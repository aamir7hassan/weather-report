<?php
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });
    $error = false;
    $msg = "";
    if(isset($_POST['get-weather'])) {
        $city = $_POST['city'];
        $api = new Api(Config::$api_key);

        if(empty($city)) {
            $error = true;
            $msg = "Please enter city name";
        }
        $data = $api->getTemperature($city);
        $data = json_decode($data,true);

        if($data['success']) {
            $weather = json_decode($data['data'],true);

        } else {
            $weather = [];
            $error = true;
            $msg = $data['message'];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--        <meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Weather Data</title>

    <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

    <style>
        #container {
            margin-top:2rem;
        }
        td, th {
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="container" id="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form role="form" method="post" action="">
                <div class="form-group">
                    <label class="">Enter City Name</label>
                    <input type="text" name="city" id="city" class="form-control" required value="<?=$_POST['city']??''?>"/>
                </div>
                <div class="form-group">
                    <input type="submit" name="get-weather" class="btn btn-success" value="Get Weather" />
                    <!--                            <a href="#" id="get-weather" class="btn btn-success" > </a>-->
                </div>
            </form>
            <hr/>
            <div class="result container">
                <?php
                    if($error) {
                        echo "<h3>".$msg."</h3>";
                    }
                ?>
                <table>
                    <tbody>
                    <?php if(isset($weather) && count($weather)>0) { ?>
                        <tr><th colspan="2">
                            <img src="http://openweathermap.org/img/w/<?php echo $weather['weather'][0]['icon']; ?>.png"
                                        class="weather-icon" /></th></tr>
                        <tr><th>Current Temperature</th><td><?=$weather["main"]['temp']??''?>°C</td></tr>
                        <tr><th>Feels Like</th><td><?=$weather["main"]['feels_like']??''?>°C</td></tr>
                        <tr><th>Humidity</th><td><?=$weather["main"]['humidity']??''.'%'?></td></tr>
                        <tr><th>Mininum Temperature</th><td><?=$weather["main"]['temp_min']??''?>°C</td></tr>
                        <tr><th>Maximum Temperature</th><td><?=$weather["main"]['temp_max']??''?>°C</td></tr>
                        <tr><th>Wind Speed</th><td><?=$weather["wind"]['speed']??''?>mph</td></tr>
                        <?php if(isset($weather["rain"]['1h'])) { ?>
                            <tr><th>Rain</th><td><?=$weather["rain"]['1h']?> mm</td></tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>

</body>
</html>
