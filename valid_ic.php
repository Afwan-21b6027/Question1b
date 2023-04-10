<?php
    require("db.php");
    $icnum = '';
    $ic_err= '';
    // This array contains the residency codes in Brunei:
    // 00 & 01 are Bruneian Citizens (Yellow)
    // 30 & 31 are Permanent Residents (Purple)
    // 50 & 51 are Temporary Residents (Green)
    $residency_num = array(00, 01, 30, 31, 50, 51); 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // ! Check residency
        if($index = array_search(trim($_POST["residency"]), $residency_num) == false){
            $ic_err = 'Invalid data: First field contains residency not registered.';
        }

        // ! Check Six digit number
        if(empty(trim($_POST["sixdigits"]))){
            $ic_err = 'Please enter in the data.';
        } elseif(strlen(strval(trim($_POST["sixdigits"]))) != 6){
            $ic_err = 'Data is not 6 digit long!';
        } elseif(trim($_POST["sixdigits"]) > 999999){
            $ic_err = 'Data entered is beyond the range.';
        }

        // ! Send to Database
        if(empty($ic_err)){
            $sql = "INSERT INTO icnumber (icnum) VALUE (?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                // ! Merge Numbers
                $icnum = trim($_POST["residency"]) ."-". trim($_POST["sixdigits"]);

                mysqli_stmt_bind_param($stmt, "s", $param_ic);
                $param_ic = $icnum;

                if(mysqli_stmt_execute($stmt)){
                    echo "IC registration successful!" ;
                } else{
                    echo "There is something wrong!";
                }
            }
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IC Numbers</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <label>IC Number</label>
        <span><input type="number" name="residency" id="residency"> - 
        <input type="number" name="sixdigits" id="sixdigits"></span><br>
        <label><?php echo $ic_err?></label>

        <input type="submit" value="submit">
    </form>
</body>
</html>