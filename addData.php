<html>
    <body>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "wpl";
        
        $conn = new mysqli($servername,$username,$password,$dbname);

        if($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }

        $pass = hash('sha256', $_POST["pass"]);
        
        $sql = "insert into users values('".$_POST["user"]."','".$_POST["email"]."','".$pass."');";

        if ($conn->query($sql) === TRUE) 
        {
            echo "true";
        } 
        else 
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
         
        
        $conn->close();
        ?>
    </body>
</html>