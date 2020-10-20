<?php /* Template Name: Booking list */ 
global $wpdb;
wp_head();

$Bookings = $wpdb->get_results( "SELECT * FROM wp_vehicle_booking INNER JOIN wp_posts ON wp_vehicle_booking.vehicle_post_id = wp_posts.ID  order by created_at DESC" );
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<div class="container">
  <h2>Booking List</h2>
  <div id="loading-animation" style="display: none; text-align:center"><img src="<?php echo admin_url ( 'images/loading-new.gif' ); ?>"/></div>        
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Vehicle</th>
        <th>status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($Bookings as $row){ ?>
      <tr>
        <td> <?=$row->first_name.' '.$row->last_name ?> </td>
    
        <td><?=$row->email ?> </td>
        <td><?=$row->post_title ?></td>
        <td><?php 
        if($row->status == 1){
           echo "Pending"; 
           } elseif($row->status == 2 ){ 
             echo "Approved";
           }else{ echo "Rejected";
            } ?>
            </td>
        <td><select name="getstatus" id="getstatus" data-id="<?=$row->id ?>" data-email="<?=$row->email ?>"><option value="1"  <?php if($row->status == 1){ echo "selected";} ?>>Pending</option><option value="2" <?php if($row->status == 2){ echo "selected";} ?>>Approved</option><option value="3" <?php if($row->status == 3){ echo "selected";} ?>>Rejected</option></select></td>
      </tr>
    <?php } ?>
      
    </tbody>
  </table>
</div>
<div id="response"></div>
<script>
$('body').on('change','#getstatus', function(e){
  var ID =$(this).attr('data-id');
  var status = $(this).val();
  var toemail = $(this).attr('data-email');

  var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' );  ?>';
  if(confirm("Are you sure you want to change status?")){
    $("#loading-animation").show(); 
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "change-status", id: ID, status: status,toemail :  toemail},
        success: function(response) {
            $('#response').html("");
            $('#response').html(response);
            $("#loading-animation").hide();
            setTimeout(function(){
            location.reload();
          }, 2000);
            return false;
        }
    });
  }
});
  </script>