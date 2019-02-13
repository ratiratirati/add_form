<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="css/fonts.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
<?php
include ('server.php');
include ('proces.php');

if(empty($_SESSION['username'])){
    header('location:index.php');
}

if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['username']);
    header('location:index.php');
}
?>
<div class="header">
    <div class="dropdown">
        <button class="dropbtn"><?php echo $_SESSION['username']?></button>
        <div class="dropdown-content">
            <a href="admin.php?logout='1'">გამოსვლა</a>
        </div>
    </div>
</div>
<div class="add_list">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">სახელი</th>
            <th scope="col">გვარი</th>
            <th scope="col">პროფესია</th>
            <th scope="col">ასაკი</th>
            <th scope="col">მობილური</th>
            <th scope="col">წაშლა</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM informacia";
        $result = mysqli_query($con,$sql);
        if(mysqli_num_rows($result)){
            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['saxeli']."</td>";
                echo "<td>".$row['gvari']."</td>";
                echo "<td>".$row['profesia']."</td>";
                echo "<td>".$row['asaki']."</td>";
                echo "<td>".$row['mobiluri']."</td>";
                echo "<td><form method='post' action='admin.php'><input type='hidden' name='deleteid' value='".$row['id']."'> <button name='delete' class=\"btn btn-danger\">წაშლა</button></form></td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>

</div>
<div class="add_form">
    <form method="post" action="home.php">
        <input id="err_4" type="text" name="saxeli" placeholder="სახელი">
        <br>
        <input id="err_5" type="text" name="gvari" placeholder="გვარი">
        <br>
        <input id="err_6" type="text" name="profesia" placeholder="პროფესია">
        <br>
        <input id="err_7" type="text" name="asaki" placeholder="ასაკი">
        <br>
        <input id="err_8" type="text" name="mobiluri" placeholder="მობილური">
        <br>
        <button name="add">დამატება</button>
        <div class="error">
            <?php include ('error.php')?>
        </div>
    </form>
</div>
</body>
</html>