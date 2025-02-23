<?php
include '../../includes/session.php';

if (isset($_POST['checkin'])) {
	$transient = $_POST['transient_id'];
	$id = $_POST['id'];

	$sql = "SELECT * FROM transient WHERE transient_id = '$id'";
	$query = $conn->query($sql);

        $row = $query->fetch_assoc();
		$transient_id = $row['transient_id'];  //
		$added = 0;


		$floor_id = $_POST['floor'];
		$room_id = $_POST['room'];
        $date_in = $_POST['date_in'];
        $time_in = $_POST['time_in'];
        $date_out = $_POST['date_out'];
        $time_out = $_POST['time_out'];


		$sql = "SELECT * FROM rooms WHERE floor_category_id = '$floor_id' AND room_category_id = '$room_id'";
				$query = $conn->query($sql);
				
					$brow = $query->fetch_assoc();
					$occupants = $brow['occupants'];
					$occupancy = $brow['occupancy'];
					$status = $brow['status'];
					$bid = $brow['id'];

				$sql = "SELECT * FROM floor_category WHERE id = '$floor_id'";
				$floor_query = $conn->query($sql);
				$floor_row = $floor_query->fetch_assoc();

				$sql = "SELECT * FROM room_category WHERE id = '$room_id'";
				$room_query = $conn->query($sql);
				$room_row = $room_query->fetch_assoc();


				if ($occupants == $occupancy) {

				
					$_SESSION['error'] = '"' .$floor_row['floor_name']. '&nbsp;-&nbsp;' .$room_row['room_name']. '" Is Full';
					header('location: ../../pages/transient.php');
					
						
					}else if ($status == 1) {
							$_SESSION['error'] = '"' .$floor_row['floor_name']. '&nbsp;-&nbsp;' .$room_row['room_name']. '" Is Unavailable';
							header('location: ../../pages/transient.php');		
					}else{
        
        // $sql = "SELECT * FROM transient WHERE transient_id = '$transient'";
	    // $query = $conn->query($sql);
		$sql = "INSERT INTO checkin (transient_id, floor_id, room_id, date_in, time_in, date_out, time_out) VALUES ('$transient_id', '$floor_id', '$room_id', '$date_in', '$time_in', '$date_out', '$time_out')";
			if ($conn->query($sql)) {
			$added++;
			$sql = "UPDATE transient SET status = $added WHERE transient_id = '$transient_id'";
			$conn->query($sql);
			
			$sql = "UPDATE rooms SET occupants = occupants + 1 WHERE floor_category_id = '$floor_id' AND room_category_id = '$room_id'";
			$conn->query($sql);	
			

			$_SESSION['success'] = 'Transient "'.$transient_id.'" Checked In Successfully';

	// Activity Log
	$sqlname = "SELECT * FROM transient WHERE transient_id = '$id'";
	$query1 = $conn->query($sqlname);
	$row1 = $query1->fetch_assoc();
	$firstname = $row1['firstname'];
	$lastname = $row1['lastname'];

	$sql = "SELECT * FROM admin WHERE id = '".$_SESSION['admin']."'";
	$query = $conn->query($sql);
	$row = $query->fetch_assoc();
	$admin = $row['id'];

	$sql = "INSERT INTO activity_logs (admin_id, details) VALUES ('$admin', 'Checked-In ".$firstname." ".$lastname.".')";
	$conn->query($sql);
	// end Activity log
			
		
	

	}
}
} else {
	$_SESSION['error'] = 'Fill up add form first';
	header('location: ../../pages/transient.php');
}

header('location: ../../pages/checkin.php');
