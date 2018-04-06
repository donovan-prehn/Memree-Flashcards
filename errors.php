<?php  if (count($errors) > 0) : ?>
  	<?php foreach ($errors as $error) : ?>
		<div class="alert alert-danger">
		  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  <strong>Login Failed</strong> <?php echo $error ?>
		</div>
  	<?php endforeach ?>
<?php  endif ?>