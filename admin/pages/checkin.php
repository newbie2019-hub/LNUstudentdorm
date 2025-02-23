<?php include '../includes/session.php'; ?>
<?php include '../includes/header.php'; ?>
<title>LNU Dormitory | Check In Status</title>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include '../includes/navbar.php'; ?>
  <?php include '../includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Checked In Trasient Record List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-user"></i> Admin</a></li>
        <li>Transient Management</li>
        <li class="active">Checked-In Transient</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <?php
        if(isset($_SESSION['error'])){
          echo "<script type='text/javascript'>
                  toastr.error('".$_SESSION['error']."', 'Error!')
                </script>";
          unset($_SESSION['error']);
        }
    
        if(isset($_SESSION['success'])){
          echo "<script type='text/javascript'>
                  toastr.success('".$_SESSION['success']."', 'Success!')
                </script>";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
          <div class="box-body">
            <div class="table-responsive">
              <table id="example" class="table table-bordered table-striped">
                <thead>

                  <th>Transient ID</th>
                  <th>Name</th>
                  <th>Room</th>
                  <th>Check In</th>
                  <th>Check Out</th>
                  <th>Status</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT *, checkin.id AS checkid, checkin.status AS instat FROM checkin LEFT JOIN transient ON transient.transient_id=checkin.transient_id 
                    LEFT JOIN floor_category ON floor_category.id=checkin.floor_id LEFT JOIN room_category ON room_category.id=checkin.room_id";
                    
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      if ($row['instat']) {
                        $status = '<span class="label label-danger">Checked Out</span>';
                      } else {
                        $status = '<span class="label label-success">Checked In</span>';
                      }
                      echo "
                        <tr>
                          
                          <td>".$row['transient_id']."</td>
                          <td>".$row['firstname']. ' ' .$row['lastname']."</td>
                          <td>".$row['floor_name'].'-'.$row['room_name']."</td>
                          <td>" . date('M d, Y', strtotime($row['date_in'])) . ' ' . date('h:ia', strtotime($row['time_in'])) . "</td>
                          <td>" . date('M d, Y', strtotime($row['date_out'])) . ' ' . date('h:ia', strtotime($row['time_out'])) . "</td>
                          <td>".$status."</td>
                          <td>  
                        <button class='btn btn-danger btn-sm checkout btn-rounded' data-id='".$row['checkid']."'><i class='fa fa-sign-out'></i></button>
                          </td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include '../includes/footer.php'; ?>
  <?php include '../components/transient_modal.php'; ?>
</div>
<?php include '../includes/scripts.php'; ?>
<script>
$(function(){

  $(document).on('click', '.checkout', function(e){
    e.preventDefault();
    $('#checkout').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: '../php/check_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.checkid').val(response.checkid);

      $('.trans').val(response.transient_id);

      $('#checkout_transient_id').val(response.transient_id).html(response.transient_id);
      $('#checkout_firstname').val(response.firstname).html(response.firstname);
      $('#checkout_lastname').val(response.lastname).html(response.lastname);

      $('#checkout_floor').val(response.floor_id);
      $('#checkout_floor').html(response.floor_name);
      $('#checkout_room').val(response.room_id);
      $('#checkout_room').html(response.room_name);
      
      $('#checkout_date_in').val(response.date_in).html(response.date_in);
      $('#checkout_time_in').val(response.time_in).html(response.time_in);
      $('#checkout_date_out').val(response.date_out).html(response.date_out);
      $('#checkout_time_out').val(response.time_out).html(response.time_out);
      

      

    }
  });
}

</script>
</body>
</html>
