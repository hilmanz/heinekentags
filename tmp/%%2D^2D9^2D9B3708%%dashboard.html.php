<?php /* Smarty version 2.6.13, created on 2015-09-02 17:24:58
         compiled from application/web/apps/dashboard.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'array_rand', 'application/web/apps/dashboard.html', 15, false),)), $this); ?>
<div class="page_section" id="home">
    <div id="homeBanner">
        <div id="bannerwrapper">
		
            <div class="flexslider">
              <ul class="slides">
			    <?php if ($this->_tpl_vars['list']): ?>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?> 
					
                	<li>
						<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['list'][$this->_sections['i']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?> 
						<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['numbr']%2 == 1): ?>
							<div class="box">
							
								<?php $this->assign('randomindex', array_rand($this->_tpl_vars['animasiclass'])); ?> 
								<div class="imgbox <?php echo $this->_tpl_vars['animasiclass'][$this->_tpl_vars['randomindex']]; ?>
 animated  "><img src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['images_thumb']; ?>
" /></div>
								<div class="imgcaption">
									<div class="thumbuser"><img src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['profile_picture']; ?>
" /></div>
									<a href="#">@<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['username']; ?>
</a>
								</div><!-- end .imgcaption -->
							</div><!-- end .box -->
						<?php else: ?>
							<div class="box">
							
								<?php $this->assign('randomindex', array_rand($this->_tpl_vars['animasiclass'])); ?> 
								<div class="imgbox <?php echo $this->_tpl_vars['animasiclass'][$this->_tpl_vars['randomindex']]; ?>
animated"><img src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['images_thumb']; ?>
" /></div>
								<div class="imgcaption">
									<div class="thumbuser"><img src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['profile_picture']; ?>
" /></div>
									<a href="#">@<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']][$this->_sections['j']['index']]['username']; ?>
</a>
								</div><!-- end .imgcaption -->
							</div><!-- end .box -->
						<?php endif; ?>
                    	<?php endfor; endif; ?>
                    </li>
                	<?php endfor; endif; ?>
				<?php endif; ?>	
              </ul>
            </div><!-- end .flexslider -->
        </div> <!-- end #bannerwrapper -->
    </div><!-- end #homeBanner -->
</div><!-- end #home -->
<script>
var basedomain="<?php echo $this->_tpl_vars['basedomain']; ?>
";	
<?php echo '
setInterval(function(){
tarik();



}, 10000);
function tarik()
{
	var items = Array(\'fadeInLeft\',\'fadeInDown\',\'fadeInRight\',\'fadeInUp\');
	 $.ajax({
				\'type\': \'POST\',
				\'url\': basedomain+\'home/ajaxPaging\',
				\'dataType\':\'json\',
				\'success\': function(result){
							var str="";
							str +=\'<div class="flexslider">\' ;
							str +=\' <ul class="slides">\';
						$.each(result.data,function(k,v){
							str +=\'<li>\';
							$.each(v,function(kx,vx){
							
								
									str +=\'<div class="box">\';
									str +=\'<div class="imgbox \'+items[Math.floor(Math.random()*items.length)]+\' animated  "><img src="\'+vx.images_thumb+\'" /></div>\';
									str +=\'<div class="imgcaption">\';
									str+=\'<div class="thumbuser"><img src="\'+vx.profile_picture+\'" /></div>\';
									str+=\'<a href="#">@\'+vx.username+\'</a>\';
									str+=\'</div>\';
									str+=\'</div>\';
								
								
							});
							str +=\'</li>\';
							
						});
							str +="</ul>";
							str +="</div>";
						$(\'#bannerwrapper\').html(str);
						 $(\'.flexslider\').flexslider({
							animation: "fade",
							controlNav: false,              
							directionNav: false,          
							slideshowSpeed: 3000,        
							animationSpeed: 600,  
						  });
					}
			});

}

'; ?>

</script>