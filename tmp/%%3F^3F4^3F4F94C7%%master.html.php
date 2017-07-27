<?php /* Smarty version 2.6.13, created on 2015-01-16 15:11:30
         compiled from application/admin//master.html */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<?php echo $this->_tpl_vars['meta']; ?>

<body>
  <div id="body">
    <div id="page">
      <?php if ($this->_tpl_vars['pages'] != 'login' && $this->_tpl_vars['pages'] != 'logout'): ?>
      		  <?php echo $this->_tpl_vars['header']; ?>

      <?php endif; ?>
        <div id="thecontent">
             <?php echo $this->_tpl_vars['mainContent']; ?>

        </div><!-- /#thecontent -->
		  <?php echo $this->_tpl_vars['footer']; ?>

    </div><!-- end #page -->
  </div><!-- end #body -->    
</body>
</html>