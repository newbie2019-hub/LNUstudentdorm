<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include '../../includes/session.php';

require '../../phpmailer/vendor/autoload.php';

include('../../includes/conn.php');

require_once '../../includes/config.php';

	if(isset($_POST['add'])){
		$event_category = $_POST['event_category'];
		$description = $_POST['description'];
		$location = $_POST['location'];
        $date = $_POST['date'];
		$time_start = $_POST['time_start'];
		$time_end = $_POST['time_end'];


$sql = "SELECT * FROM `event_category` WHERE id = $event_category";
$query = $conn->query($sql);
$row = $query->fetch_assoc();
$call = $row['event_name'];

		// EMAIL

$sql = "SELECT email FROM `students`";
$query = $conn->query($sql);
// $query = $conn->query($sql);
// while ($row = $query->fetch_assoc()) {
 
while ($row = $query->fetch_assoc()) {
$email = $row['email'];




  $output='<p>Dear Students,</p>';
  $output.='<p>This is LNU Student Dormitory</p>';
  $output.='<p>Announcement for LNU Student Dormitory Boarders</p>';
  $output.='<hr>';
  $output.='<h3>WHAT : '.$call.'</h3>';
  $output.='<h3>WHERE : '.$location.'</h3>';
  $output.='<h3>WHEN : '. date('M d, Y', strtotime($date)) .' | '. date('h:ia', strtotime($time_start)) .' to '. date('h:ia', strtotime($time_end)) .'</h3>';
  $output.='<h3>Description : '.$description.'</h3>';
  $output.='<p>Thank You!.</p>';   	
  $output.='<p>LNU Dormitory Administrator</p>';
  $body = $output; 
 
  $phpmailer = new PHPMailer(true);
  try {
	// $phpmailer->isSMTP();
	// $phpmailer->Host = 'smtp.mailtrap.io';
	// $phpmailer->SMTPAuth = true;
	// $phpmailer->Port = 2525;
	// $phpmailer->Username = '4409643b784de2';
	// $phpmailer->Password = 'd1b16df0cff195';


	$phpmailer->isSMTP();
	$phpmailer->Host = 'smtp.gmail.com';
	$phpmailer->SMTPAuth = true;
	$phpmailer->Username = Database::USERNAME;
	$phpmailer->Password = Database::PASSWORD;
	$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$phpmailer->Port = 587;

  
	//Recipients
   $phpmailer->setFrom('lnustudentdorm2021@gmail.com', 'LNU Student Dormitory');
   $phpmailer->addAddress($email);     //Add a recipient
   $phpmailer->addReplyTo('lnustudentdorm2021@gmail.com', 'Information');
  
	 //Content
   $phpmailer->isHTML(true);                                  //Set email format to HTML
   $phpmailer->Subject = 'LNU Dormitory Announcement';
   $phpmailer->Body    = $body;

   $phpmailer->send();
   
   echo 'Message has been sent';
   $_SESSION['email_success'] = 'Notification sent!';
  } catch (Exception $e) {
   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
 }





// END EMAIL
		
		$sql = "INSERT INTO event (event_category_id, description, location, date, time_start, time_end) VALUES ('$event_category', '$description', '$location', '$date', '$time_start', '$time_end')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Event added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: ../../pages/event.php');
?>