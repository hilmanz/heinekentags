<?php /* Smarty version 2.6.13, created on 2015-01-16 15:11:30
         compiled from application/admin/apps/dashboard.html */ ?>
<!-- ki -->
<div class="page_section" id="project-page">
    <div id="container">
        <div class="titlebox">
            <h2 class="fl"><span class="icon-books">&nbsp;</span> Heineken #TakeMeToDreamIsland Campaign Monitor</h2>
        </div><!-- end .titlebox -->
        <div class="content">
            <div class="summary">
            </div><!-- end .summary -->

        
            <div id="new-project" class="boxcontent">
	 <div class="newspage">
			         <div class="newspage">
			  <?php if ($this->_tpl_vars['list']): ?>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
if ($this->_sections['i']['start'] < 0)
    $this->_sections['i']['start'] = max($this->_sections['i']['step'] > 0 ? 0 : -1, $this->_sections['i']['loop'] + $this->_sections['i']['start']);
else
    $this->_sections['i']['start'] = min($this->_sections['i']['start'], $this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] : $this->_sections['i']['loop']-1);
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
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
					<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['n_status'] == 0): ?>				
					<table width="100%">
					<td width='5%'> <?php echo $this->_sections['i']['index']; ?>
</td>
					<td width='20%'>
						
						<img  src="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['profile_picture']; ?>
" height="100" width="100" style="display: inline; margin: 0px 7px 0px 0px; border: solid 1px #eee; float: left" ></td>
						
						<td width='40%'><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['username']; ?>
<br><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['posts']; ?>
 </td>
						<td width='10%'><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['referance']; ?>
</td>
						<td width='5%'> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['date']; ?>
</td>
						<td width='10%'>
						<a href="<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['link']): ?> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['link']; ?>
  <?php else: ?> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['link_video']; ?>
 <?php endif; ?>" target="_blank"> Link </a>

					
											<?php endif; ?>
						
						
						
						</td> 
						
					</table>	
						<?php endfor; endif; ?>
				<?php endif; ?>		</div><br><br><br><br>
            </div><!-- end #wizard -->
            
        </div><!-- end .content -->
    </div><!-- end #container -->
</div><!-- end #home -->


<?php echo '
	 <script type="text/javascript">
	 function func1() {
			$(\'.activec\').addClass(\'hide\');
			$(\'.inactivec\').addClass(\'hide\');
		}
	window.onload=func1;
	</script>
	
	<script type="text/javascript">
		 
		$().ready(function(){
			$(\'.FormInput\').submit(function(e){
				  e.preventDefault();
					 $.ajax({
					
							\'type\': \'POST\',
							\'url\': \'\'+basedomain+\'home/status\',
							\'data\': $(this).serialize(),
							\'dataType\':\'json\',
							\'success\': function(result){
							
						
						if(result.status == \'1\' )
								{
									location.reload();
								}else if (result.status == \'2\')
								{
									location.reload();
								}
							
							
							
							}
						});
					});
				 });
		   

	'; ?>

	</script>