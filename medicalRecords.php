<html>
    <body>
        <?php

        

        function addToDatabase()
        {
            $fname = $_POST["firstname"];
            $mname = $_POST["middlename"];
            $lname = $_POST["lastname"];
            $gender = $_POST["gender"];
            $bGroup = $_POST["blood"];
            $age = $_POST["age"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $date = $_POST["date"]; 
            $time = $_POST["time"]; 
            $dept = $_POST["dept"]; 
            $purpose = $_POST["purpose"];
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "wpl";
            
            $conn = new mysqli($servername,$username,$password,$dbname);

            if($conn->connect_error)
            {
                die("Connection failed: " . $conn->connect_error);
            }

            

            $deptcsv = "";

            foreach($dept as $i)
            {
                $deptcsv = $deptcsv.$i.",";    
            }

            $deptcsv = substr($deptcsv,0,-1);
        
            $sql = "insert into medical values('$fname','$mname','$lname','$gender','$bGroup',$age,'$email',$phone,'$date','$time','$deptcsv','$purpose');";

            if ($conn->query($sql) === TRUE) 
            {
                echo "true";
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            
            $conn->close();
        
        }

        function fileUpload()
        {
            $file = $_FILES['medicFile'];
            $targetDir = 'Uploads/';
            $targetFile = $targetDir . basename($file['name']);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check file size (max 10 MB)
            if($file['size'] > 10000000)
            {
                return false;
            }

            // Move file to target directory
            if(!file_exists($targetFile))
            {
                if(move_uploaded_file($file['tmp_name'], $targetFile))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

        }

        function sendConfirmation($emails, $doctor)
        {
            $fname = $_POST["firstname"];
            $mname = $_POST["middlename"];
            $lname = $_POST["lastname"];
            $gender = $_POST["gender"];
            $bGroup = $_POST["blood"];
            $age = $_POST["age"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $date = $_POST["date"]; 
            $time = $_POST["time"]; 
            $dept = $_POST["dept"]; 
            $purpose = $_POST["purpose"];

            $deptcsv = "";

            foreach($dept as $i)
            {
                $deptcsv = $deptcsv.$i.",";    
            }

            $deptcsv = substr($deptcsv,0,-1);

            $from_email         = 'doclabwebsite@gmail.com'; //from mail, sender email address

            
            //Load POST data from HTML form
            $sender_name = "Doclab"; //sender name
            $reply_to_email = 'doclabwebsite@gmail.com'; //sender email, it will be used in "reply-to" header
            $subject     = "Appointment Booking Confirmation"; //subject for the email
            

            if($doctor)
            {
                $recipient_email = $emails;
                $message     = "New Booking at $date $time\n\n
                                Patient Details:\n
                                Name: $fname $mname $lname\n
                                Gender: $gender\n
                                Blood Group: $bGroup\n
                                Age: $age\n
                                Phone No.: $phone\n
                                Purpose of Checkup: $purpose\n
                                Medical Record Attached
                                "; 

                $tmp_name = $_FILES['medicFile']['tmp_name']; // get the temporary file name of the file on the server
                $name     = $_FILES['medicFile']['name']; // get the name of the file
                $size     = $_FILES['medicFile']['size']; // get size of the file for size validation
                $type     = $_FILES['medicFile']['type']; // get type of the file
                $error     = $_FILES['medicFile']['error']; // get the error (if any)

                //validate form field for attaching the file
                if($error > 0)
                {
                    die('Upload error or No files uploaded');
                }

                $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
                $content = fread($handle, $size); // reading the file
                fclose($handle);                 // close upon completion
                $encoded_content = chunk_split(base64_encode($content));

                $boundary = md5("random"); // define boundary with a md5 hashed value
                //header
                $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
                $headers .= "From:".$from_email."\r\n"; // Sender Email
                $headers .= "Reply-To: ".$reply_to_email."\r\n"; // Email address to reach back
                $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
                $headers .= "boundary = $boundary\r\n"; //Defining the Boundary
                    
                //plain text
                $body = "--$boundary\r\n";
                $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $body .= chunk_split(base64_encode($message));

                $body .= "--$boundary\r\n";
                $body .="Content-Type: $type; name=".$name."\r\n";
                $body .="Content-Disposition: attachment; filename=".$name."\r\n";
                $body .="Content-Transfer-Encoding: base64\r\n";
                $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
                $body .= $encoded_content; // Attaching the encoded file with email
            }
            else
            {
                $recipient_email = $email;
                $body = "Booking Confirmed For $fname $mname $lname at $date $time for $deptcsv departments";
                $headers = "From: doclabwebsite@gmail.com";
            }
            
            $sentMailResult = mail($recipient_email, $subject, $body, $headers);
        
            if($sentMailResult )
            {
                echo "Mail Confirmation Sent";
            }
            else
            {
                echo false;
            }

        }

        set_time_limit(300);
        addToDatabase();
        fileUpload();
        sleep(10);
        sendConfirmation("",false);
        sendConfirmation("aryan.sheth@somaiya.edu",true);
        

        ?>
    </body>
</html>