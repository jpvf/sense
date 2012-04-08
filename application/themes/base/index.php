<?php $this->template->render_partial('header'); ?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="#">Sense Framework</a>    
    </div>
  </div>
</div>

<div class="container">
	<?php echo $template_main ?> 
</div> <!-- /container -->
<?php $this->template->render_partial('footer'); ?>