<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="container-fluid">
    <!DOCTYPE html>
<html>
  <head>
    <style>
       #map {
        height: 400px;
        width: 100%;
       }
    </style>
  </head>
  <body>
    <h3>Google Mapped Locations</h3>
    <div id="map"></div>
    <div class="modal fade" tabindex="-1" role="dialog" id="contModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Contact</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?=$this->Form->create(null, ['url'=>['controller' => 'Maps', 'action' => 'index']]);?>
            <div class="form-group">
                <?=$this->Form->control(null, ['type' => 'textarea', 'name'=>'message', 'class'=>'form-control', "id"=>"message", "label"=>"Message", "rows"=>"5"]);?>
               
            </div>
       <?= $this->Form->end()?>
      </div>
      <div class="modal-footer">
        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-primary', 'id'=>'submit']) ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <script>
            $( document ).ready(function() {
    $("#submit").click(function(){
        var msg = $("#message").val();
        $("#message").val('');
        $('#contModal').modal('hide');
        $.post( "Map/email", {'msg': msg}).done(function( data ) {
        alert(data);
        location.reload();
        });
    });
});
      function initMap() {
        var usa = {lat: 39.50, lng: -98.35};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: usa
        });
        <?php foreach ($locations as $key => $value) :?>
        var loc = {lat: <?=$value['lat']?>, lng: <?=$value['lng']?>};
        var marker<?=$value['id']?> = new google.maps.Marker({
          position: loc,
          map: map
        });
        var contentString<?=$value['id']?> = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h3 id="firstHeading" class="firstHeading"><?=$value['street'] ?><br /><?=$value['city']?>, <?=$value['state']?><br /><?=$value['zip']?></h3>'+
      '<div id="bodyContent">'+
      '<p><button class="mail" data-toggle="modal" data-target="#contModal">Contact</button>';

  var infowindow<?=$value['id']?> = new google.maps.InfoWindow({
    content: contentString<?=$value['id']?>
  });
  marker<?=$value['id']?>.addListener('click', function() {
    infowindow<?=$value['id']?>.open(map, marker<?=$value['id']?>);
  });
        <?php endforeach?>
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEUCzU6bdFOnHe-yt-VWMbnfMhJmCfTdk&callback=initMap">

    </script>
  </body>
</html>
    
</div>
