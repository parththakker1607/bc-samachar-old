<?php

// if the from is loaded from WordPress form loader plugin, 
// the phpfmg_display_form() will be called by the loader 
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




<div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>
            
            
<ol class='phpfmg_form' >
<h3>JOIN AS MEMBER</h3>
<h4>Personal Information</h4>

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>First Name</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Last Name</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>Email(Login Id)</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_2"  id="field_2" value="<?php  phpfmg_hsc("field_2", ""); ?>" class='text_box'>
	<div id='field_2_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_3_div'><div class='col_label'>
	<label class='form_field'>Password</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="password" name="field_3"  id="field_3" value="<?php  phpfmg_hsc("field_3"); ?>" class='text_box'>
	<div id='field_3_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_4_div'><div class='col_label'>
	<label class='form_field'>Confirm Password</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="password" name="field_4"  id="field_4" value="<?php  phpfmg_hsc("field_4"); ?>" class='text_box'>
	<div id='field_4_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_5_div'><div class='col_label'>
	<label class='form_field'>Gender</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_5', "Male|Female|Prefer Not to Answer", true );?>
	<div id='field_5_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_6_div'><div class='col_label'>
	<label class='form_field'>Age</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_6', "18-24|25-34|35-44|45-54|55-64|65 or Above|Prefer Not to Answer", true );?>
	<div id='field_6_tip' class='instruction'></div>
	</div>
</li>
<?php phpfmg_dependent_dropdown( 'field_7' );?>
<li class='field_block' id='field_8_div'><div class='col_label'>
	<label class='form_field'>Qualification</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_8"  id="field_8" value="<?php  phpfmg_hsc("field_8", ""); ?>" class='text_box'>
	<div id='field_8_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_9_div'><div class='col_label'>
	<label class='form_field'>Specialisation</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_9"  id="field_9" value="<?php  phpfmg_hsc("field_9", ""); ?>" class='text_box'>
	<div id='field_9_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_10_div'><div class='col_label'>
	<label class='form_field'>Profession</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_10"  id="field_10" value="<?php  phpfmg_hsc("field_10", ""); ?>" class='text_box'>
	<div id='field_10_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_11_div'><div class='col_label'>
	<label class='form_field'>Organisation / Association</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_11"  id="field_11" value="<?php  phpfmg_hsc("field_11", ""); ?>" class='text_box'>
	<div id='field_11_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_12_div'><div class='col_label'>
	<label class='form_field'>Designation</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_12"  id="field_12" value="<?php  phpfmg_hsc("field_12", ""); ?>" class='text_box'>
	<div id='field_12_tip' class='instruction'></div>
	</div>
</li>
<?php phpfmg_dependent_dropdown( 'field_13' );?>
<li class='field_block' id='field_14_div'><div class='col_label'>
	<label class='form_field'>Address</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<textarea name="field_14" id="field_14" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_14"); ?></textarea>

	<div id='field_14_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_15_div'><div class='col_label'>
	<label class='form_field'>PIN</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_15"  id="field_15" value="<?php  phpfmg_hsc("field_15", ""); ?>" class='text_box'>
	<div id='field_15_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_16_div'><div class='col_label'>
	<label class='form_field'>Landline No</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_16"  id="field_16" value="<?php  phpfmg_hsc("field_16", ""); ?>" class='text_box'>
	<div id='field_16_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_17_div'><div class='col_label'>
	<label class='form_field'>Mobile No</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_17"  id="field_17" value="<?php  phpfmg_hsc("field_17", ""); ?>" class='text_box'>
	<div id='field_17_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_18_div'><div class='col_label'>
	<label class='form_field'>Description</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<textarea name="field_18" id="field_18" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_18"); ?></textarea>

	<div id='field_18_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_19_div'><div class='col_label'>
	<label class='form_field'>Upload Photo</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="file" name="field_19"  id="field_19" value="" class='text_box' onchange="fmgHandler.check_upload(this);">
	<div id='field_19_tip' class='instruction'></div>
	</div>
</li>


<li class='field_block' id='phpfmg_captcha_div'>
	<div class='col_label'><label class='form_field'>Security Code:</label> <label class='form_required' >*</label> </div><div class='col_field'>
	<?php phpfmg_show_captcha(); ?>
	</div>
</li>


            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	
				
                <input type='submit' value='Submit' class='form_button'>

				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				


                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            </li>
            
</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div> 
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
Your form has been sent. Thank you!
</div>

            
            






<?php
			
    phpfmg_javascript($sErr);

} 
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>
<style type='text/css'>
<?php 
if( !$formOnly ){
    echo"
body{
    margin-left: 18px;
    margin-top: 18px;
}

body{
    font-family : Verdana, Arial, Helvetica, sans-serif;
    font-size : 13px;
    color : #474747;
    background-color: transparent;
}

select, option{
    font-size:13px;
}
";
}; // if
?>

ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}
.phpfmg_form h3{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	font-weight:bold;
	color:#b5161c;
}

.phpfmg_form h4{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	font-weight:bold;
	color:#1b8bbd;
}

ol.phpfmg_form input, ol.phpfmg_form textarea, ol.phpfmg_form select{
    border: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
	height:28px;
}

ol.phpfmg_form li{
    margin-bottom:8px;
    clear:both;
    display:block;
    overflow:hidden;
	width: 100%
}
label.form_choice_text {
margin-top: 8px;
position: absolute;
}
.col_label {
margin-bottom: 3px;
}


.form_field, .form_required{
    font-weight : bold;
	font-family:sans-serif;
	color: #666;
}

.form_required{
    color:red;
    margin-right:8px;
}

.field_block_over{
}

.form_submit_block{
    padding-top: 3px;
}

.text_box, .text_area, .text_select {
    width:300px;
}

.text_area{
    height:80px;
}

.form_error_title{
    font-weight: bold;
    color: red;
}

.form_error{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}

.form_error_highlight{
    background-color: #F4F6E5;
    border-bottom: 1px dashed #ff0000;
}

div.instruction_error{
    color: red;
    font-weight:bold;
}

hr.sectionbreak{
    height:1px;
    color: #ccc;
}

#one_entry_msg{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}


#frmFormMailContainer input[type="submit"]{
    padding: 10px 25px; 
    font-weight: bold;
    margin-bottom: 10px;
    background-color: #FAFBFC;
	width:100px;
	height:40px;
}

#frmFormMailContainer input[type="submit"]:hover{
    background-color: #E4F0F8;
}

<?php phpfmg_text_align();?>    



</style>

<?php
}
# end of css
 
# By: formmail-maker.com
?>