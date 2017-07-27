<?php /* Smarty version 2.6.13, created on 2015-01-01 22:38:56
         compiled from application/web/apps/home.html */ ?>
<div id="home" class="mainContent">
	<div class="col-12">
    	<div class="mainSlider">
        	<div class="flexslider">
              <ul class="slides">
			   <?php if ($this->_tpl_vars['listBanner']): ?>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['listBanner']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						 <a href="<?php echo $this->_tpl_vars['listBanner'][$this->_sections['i']['index']]['url']; ?>
"> <img src="<?php echo $this->_tpl_vars['basedomain']; ?>
assets/content/slide/slide1.jpg" /> </a>
						</li>
					<?php endfor; endif; ?>
				<?php endif; ?>	
              </ul><!--end.slides-->
            </div><!--end.flexslider-->
        </div><!--end.mainSlider-->
        <div class="rows">
        	<div class="boxContent">
            	<div class="introText">
                	<h2>About Shinkenjuku</h2>
                    <p><span class="kutip1"></span>Shinkenjuku adalah metode pembelajaran dari Jepang dengan dasar pemikiran bahwa mengerti Matematika itu menyenangkan. Siswa diajak untuk belajar lebih riang, sehingga kemampuan berpikirnya pun berkembang. Dengan semangat,  "Aku Pasti Bisa!" anak pasti dapat menyelesaikan setiap soal Matematika dengan cara yang lebih menyenangkan.<span class="kutip2"></span></p>
                    <a href="#" class="seeMore">See More</a>
                </div><!--end.introText-->
            </div><!--end.boxContent-->
        </div><!--end.rows-->
        <div class="rows">
        	<div class="col-left menuHome">
            	<div class="contentMenu">
                	<div class="bigMenu">
                    	<a href="#" class="systemMenu firstChild">System</a>
                    </div>
                    <div class="bigMenu">
                    	<a href="#" class="sampleBook">Sample Book</a>
                    </div>
                    <div class="bigMenu">
                    	<a href="#" class="registraionMenu">Registration</a>
                    </div>
                </div><!--end.contentMenu-->
            </div><!--end.col-left-->
            <div class="col-center newsHome">
                <div class="boxContent">
            		<div class="newsHighlights">
                    	<div class="tittle">
                        	<h2>News & Updates</h2>
                        </div>
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
							<div class="newsFlash">
								<div class="newsImage">
									<img src="<?php echo $this->_tpl_vars['basedomain']; ?>
assets/content/news/news1.jpg" />
								</div>
								<div class="newsText">
									<h4 class="titleFlash"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['title']; ?>
</h4>
									<span class="dateNews">2014/08/09</span>
									<p class="flashText"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['content']; ?>
<a class="seeMore" href="<?php echo $this->_tpl_vars['basedomain']; ?>
news/contentview_news?id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id_news']; ?>
">See More</a></p>
								</div>
							</div><!--end.newsFlash-->
							<?php endfor; endif; ?>
						<?php endif; ?>	
                	</div><!--end.newsHighlights-->
                </div><!--end.boxContent-->
            </div><!--end.col-3-->
            <div class="col-right">
            	<a href="#" class="readMangaImage">
                	<img src="<?php echo $this->_tpl_vars['basedomain']; ?>
assets/images/read-manga.jpg"  />
                </a>
            </div><!--end.col-3-->
        </div><!--end.rows-->
    </div><!--end.col-12-->
</div><!--end.mainContent-->
<?php echo '
<script>
$(window).load(function() {
  $(\'.flexslider\').flexslider({
    animation: "slide"
  });
});
'; ?>

</script>