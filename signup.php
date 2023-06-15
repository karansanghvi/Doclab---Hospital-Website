<html>
    <head>
        <link rel="stylesheet" href="./assets/css/otpstyle.css">
    </head>
    <body onload = "loadSite()">
        
        <div class="container">
            <h3 class="title">OTP Verification</h3>
            <p class="sub-title">
              Enter the OTP you received to
              <span id="email"></span>
            </p>
            <div class="wrapper">
                <form onsubmit = "return verifyOTP()">
                    <input type="text" class="field 1" id="otpField" maxlength="6">
                    <input type="submit" value="Submit" style="padding-top: 10px; height: 50px; width: 100%; border: none; 
                     background-color: white; color: #0090e4; font-size: large;">
                </form>
              
            </div>
        </div>

        <?php

        function sendOTP()
        {
            $to_email = $_POST["email"];
            $subject = "Doclab Login OTP";
            $otp = rand(100000,999999);
            $body = "Hey, $to_email \n $otp is your One-Time Password to login to Doclab\n If you did not request to login, 
            please ignore this message";
            $headers = "From: doclabwebsite@gmail.com";
 
            if (mail($to_email, $subject, $body, $headers))
            {
                echo $otp;
            }
 
            else
            {
                echo 0;
            }
        }

        function getmail()
        {
            echo $_POST["email"];
        }
        function getuser()
        {
            echo $_POST["user"];
        }
        function getpass()
        {
            echo $_POST["pass"];
        }

        ?>
    </body>

    <script>
        
        var password = "<?php sendOTP()?>";
        var email = "<?php getmail()?>";
        var user = "<?php getuser()?>";
        var pass = "<?php getpass()?>";

        function loadSite()
        {
            document.getElementById("email").innerHTML = email;
        }
        function verifyOTP()
        {
            password = parseInt(password);
            if(password != 0)
            {
                if(document.getElementById("otpField").value == password)
                {
                    var data = new URLSearchParams();
                    data.append("user",user);
                    data.append("pass",pass);
                    data.append("email",email);

                    fetch("addData.php",{
                        method: "post",
                        body: data
                    })
                    .then(function (response){
                        window.location.replace("index.html");
                    })
                    .catch(function (err)
                    {
                        console.log(err);
                    });

                    return true;
                }
                else
                {
                    alert("Verification Failed - Invalid OTP");
                    window.location.replace("index.html");
                }

            }
            else
            {
                alert("There was an error generating your OTP\n Please Try Again");
                window.location.replace("index.html");
            }
        }
    </script>
</html> 
