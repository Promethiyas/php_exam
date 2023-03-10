<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <?php
        session_start();
        include '../common/ConnectionDB.php';
        if($_SESSION['login'] == "" || ($_POST['toPaid'] == "" && $_POST['addressBilling'] = "")){
            header('Location: ../login');
            Exit();
        }
        $result = QueryToDB("SELECT balance FROM USER WHERE UUID = \"".$_SESSION['login']."\"");
        while($row = $result->fetch_assoc()){
            $balanceUser = $row['balance'];
        }
        if ($_POST['addressBilling'] != "") {
            $today = date("Y-m-d");
            QueryToDB("INSERT INTO invoice (UUID, `date of a transaction`, amount, billing_adress, billing_city, billing_postal_code) VALUES (\"".$_SESSION['login']."\",\"".$today."\",\"".$_POST['toPaid']."\",\"".$_POST['addressBilling']."\",\"".$_POST['cityBilling']."\",\"".$_POST['postalBilling']."\")");
            QueryToDB("UPDATE user SET balance=".$balanceUser-$_POST['toPaid']." WHERE UUID =\"".$_SESSION['login']."\"");
            $resultStock = QueryToDB("SELECT ID_item FROM cart WHERE UUID = \"".$_SESSION['login']."\"");
            while($row = $resultStock->fetch_assoc()){
                QueryToDB("UPDATE stock SET available = available -1 WHERE ID_item = ".$row['ID_item']."");
            }
            QueryToDB("DELETE FROM cart WHERE UUID =\"".$_SESSION['login']."\"");
            header('Location: ../index');
            Exit();
        }
    ?>

    Your actual balance : <?php echo $balanceUser ?>
    <br>
    Total Amount : <?php echo $_POST['toPaid'] ?>
    <br>
    Balance after payment : <?php echo $balanceUser - $_POST['toPaid'] ?>
    <h3>Billing Information:</h3>
    <form action="" method="POST">
        <input type="text" name="addressBilling" placeholder="Billing Address">
        <input type="text" name="cityBilling" placeholder="Billing City">
        <input type="number" name="postalBilling" placeholder="Billing Postal Code">
        <input type="hidden" name="toPaid" value="<?php echo $_POST['toPaid'] ?>">
        <input type="submit" value="Confirm Payment">
    </form>
</body>
</html>