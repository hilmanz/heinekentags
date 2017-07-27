<?php /* Smarty version 2.6.13, created on 2015-01-07 09:44:51
         compiled from application/web/apps/contact_us.html */ ?>

<div class="page_section" id="project-page">
    <div id="container">
        <div class="titlebox">
            <h2 class="fl"><span class="icon-books">&nbsp;</span> CONTACT US </h2>
          
        </div><!-- end .titlebox -->
        <div class="content">
            <div class="summary">
                <p>CONTACT US TO RETRIVE ALL AND NEW INFORMATION FROM SHINKENJUKU</p>
            </div><!-- end .summary -->

            <form id="forms" class="forms" method="POST" action="" enctype="multipart/form-data">
            <div id="new-project" class="boxcontent">
                <section class="step1">
                    <h3>CONTACT US</h3>
				
                    <p>Fill in the data for contact us.</p>
						<label class="msg_name" style="color: green;"><?php echo $this->_tpl_vars['success']; ?>
</label>
                    <div class="row">
                        <label for="name">Name<br></label>
                        <input id="name" name="name" type="text" class="required" ><br><label class="msg_name" style="color: red;"><?php echo $this->_tpl_vars['nama_not']; ?>
</label>
                    </div><!-- end .row -->
					<div class="row">
                        <label for="email">Email<br></label>
                        <input id="email" name="email" type="text" class="required" ><br><label class="msg_name" style="color: red;"><?php echo $this->_tpl_vars['email_not']; ?>
</label>
                    </div><!-- end .row -->
					   <div class="row">
                        <label for="Pesan">Pesan<br></label>
									<textarea class="required" name="pesan" style="width: 603px; height: 111px;"></textarea>
						</label>
						<br><label class="msg_name" style="color: red;"><?php echo $this->_tpl_vars['alamatnot']; ?>
</label>
						
                    </div><!-- end .row -->
					
                 
                    <input type="hidden" name="submit" value="1">
                        <input type="submit" value="SUBMIT" class="button3"  />
						 <a href="<?php echo $this->_tpl_vars['basedomain']; ?>
contact" class="button4">CANCEL</a>
                        <small class="msg"><?php echo $this->_tpl_vars['status']['msgee']; ?>
</small>
                        <small class="msg"><?php echo $this->_tpl_vars['status']['msg']; ?>
</small>
                    </div><!-- end .row -->
                </section>
            </div><!-- end #wizard -->
            </form>
        </div><!-- end .content -->
    </div><!-- end #container -->
</div><!-- end #home -->