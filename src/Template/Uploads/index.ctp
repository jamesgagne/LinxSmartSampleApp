<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="container-fluid" >
    <section class="row">
    <div class="col-md-10">
    <div class="col-md-5 col-md-offset-6" >
   <h1> <span class="label label-default"><?= __('{0}',$msg) ?></span> </h1>
    
        <?=$this->Form->create(null, ['type' => 'file', 'url'=>['controller' => 'Uploads', 'action' => 'index']]);?>
    <fieldset class="form-group">
        <?=$this->Form->control(null, ['type' => 'file', 'name'=>'CSV', 'class'=>'form-control']);?>
    </fieldset>
    <?= $this->Form->button(__('Submit'),['class'=>'btn btn-primary', "data-toggle"=>"modal", "data-target"=>"#loadModal"]) ?>
  
    </div>
</div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="loadModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="text-align: center;">Loading</h5>
      </div>
      <div class="modal-body">
         <p style="text-align: center;">Processing Data Please Wait...&nbsp;<img src="<?= $this->request->webroot?>img/loading.gif" /></p>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
</div>
