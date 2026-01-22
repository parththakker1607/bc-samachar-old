<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "vasamsetty@yahoo.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "admin" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onClick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'CBCE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WENEQxhCHUMDkMREWkVaGR0CHZDVBTSKNLo2CKKKAVWyNjDCxMBOilo1NWzpqpWhWUjuQ1MHEwOax0jQDmxuwebmgQo/KkIs7gMAUxvKtA028WsAAAAASUVORK5CYII=',
			'2494' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYWhlCGRoCkMREpjBMZXR0aEQWCwCqYgWSyGIMrYyuQLEpAcjum7Z06crMqKgoZPcFiLQyhAQ6IOtldBANdWgIDA1BdgvIRKBLUNwCEnN0QBELDcV080CFHxUhFvcBANoqzMls+z5hAAAAAElFTkSuQmCC',
			'E903' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYQximMIQ6IIkFNLC2MoQyOgSgiIk0Ojo6NIigibkCyQAk94VGLV2auipqaRaS+wIaGAOR1EHFGMB6Uc1jwWIHpluwuXmgwo+KEIv7AL2UzjuuX7ygAAAAAElFTkSuQmCC',
			'A3D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDGUMDkMRYA0RaWRsdHZDViUxhaHRtCEQRC2hlaGVtCHR1QHJf1NJVYUtXRUZFIbkPoi6gQQRJb2goyDxUMaA6sB2oYiC3OAQEoIiB3Mww1WEQhB8VIRb3AQCE2Mzx5NmhTwAAAABJRU5ErkJggg==',
			'5DC5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkNEQxhCHUMDkMQCGkRaGR0CHRhQxRpdGwRRxAIDQGKMrg5I7gubNm1l6qqVUVHI7msFqWNoEEG2GYtYQCvEDmQxkSkgtwQEILuPNQDkZoepDoMg/KgIsbgPAINlzHqXg+EiAAAAAElFTkSuQmCC',
			'FF90' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGVqRxQIaRBoYHR2mOqCJsTYEBARgiAU6iCC5LzRqatjKzMisaUjuA6ljCIGrQ4g1YIoxYrEDm1sY0Nw8UOFHRYjFfQCR0c1UXRbx/gAAAABJRU5ErkJggg==',
			'3076' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA6Y6IIkFTGEMAZIBAcgqW1lbGRoCHQSQxaaINDo0Ojogu29l1LSVWUtXpmYhuw+kbgojmnlAsQBGBxE0OxgdUMVAbmFtYEDRC3ZzAwOKmwcq/KgIsbgPAD8lyzsJkx6tAAAAAElFTkSuQmCC',
			'48CB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjCGMIQ6hjogi4WwtjI6BDoEIIkxhog0ujYIOoggibFOYW1lbWCEqQM7adq0lWFLV60MzUJyXwCqOjAMDQWZx4hiHsMUTDsYpmC6BaubByr8qAexuA8ApCbLEigv3cYAAAAASUVORK5CYII=',
			'77B7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DGUNDkEVbGRpdGx0aRNDFGgJQxaYwtLIC1QUguy9q1bSloatWZiG5j9GBIQCorhXZXlagKGtDwBRkMRGgKFAsAFkMZCNro6MDhlgoI4rYQIUfFSEW9wEAqvDMT+Y1+JwAAAAASUVORK5CYII=',
			'B4F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYWllDA6Y6IIkFTGGYytrAEBCALNbKEMrawOgggqKO0RVJHdhJoVFLly4NXTU1C8l9AVNEWjHNEw11RTevFegWDDsYMPSC3dzAgOLmgQo/KkIs7gMA6kvMwgoe47QAAAAASUVORK5CYII=',
			'34E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RAMYWllDHVqRxQKmMExlbWCY6oCsspUhFCgWEIAsNoXRlbWB0UEEyX0ro5YuXRq6MmsasvumiLQiqYOaJxrqiiEGdAuaHUC3tKK7BZubByr8qAixuA8Ab2fK1W/FX5gAAAAASUVORK5CYII=',
			'685E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUMDkMREprC2sjYwOiCrC2gRaXRFF2sAqpsKFwM7KTJqZdjSzMzQLCT3hQDNY2gIRNXbKtLogEXMFU0M5BZGR0cUMZCbGUIZUdw8UOFHRYjFfQDMV8pR42JKuAAAAABJRU5ErkJggg==',
			'231A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nM2QsQ2AMAwEP0U28EDJBi5shmCKUGSDkB3IlKBUjkIJAl/3+pdORpsu4U+84ufZCwqyzahQhmAPJuOMLQqY7TpfFBfI+tW2tHqs1frx0Ou4gC0Up2JdUs+GHiWatqpenMYh++p/D3LjdwL1LMpURqj00gAAAABJRU5ErkJggg==',
			'FC1E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMZQxmmMIYGIIkFNLA2OoQwOjCgiIk0OGIRA+qFiYGdFBo1bdWqaStDs5Dch6YOr5gDhhjQLRhijEBXO6K4eaDCj4oQi/sAH4XLcgiJfDMAAAAASUVORK5CYII=',
			'C03A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WEMYAhhDGVqRxURaGUNYGx2mOiCJBTSyAtUEBAQgizWINDo0OjqIILkvatW0lVlTV2ZNQ3IfmjqEWENgaAiGHYEo6iBuQdULcTMjithAhR8VIRb3AQDFQcyATL63cwAAAABJRU5ErkJggg==',
			'275B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DHUMdkMREpjA0ujYwOgQgiQW0QsREkHW3MrSyToWrg7hp2qppSzMzQ7OQ3RcAhA2BKOYxOjA6gMSQzWMFQ1QxESBkdHRE0RsaClQRyoji5oEKPypCLO4DAFukypQIVKCpAAAAAElFTkSuQmCC',
			'103C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGaYGIImxOjCGsDY6BIggiYk6sLYyNAQ6sKDoFWl0aHR0QHbfyqxpK7OmrsxCdh+aOoQY0DxUMWx2YHFLCKabByr8qAixuA8A2yvIyKhY83YAAAAASUVORK5CYII=',
			'7686' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGaY6IIu2srYyOjoEBKCIiTSyNgQ6CCCLTRFpYHR0dEBxX9S0sFWhK1OzkNzH6CAKNM8RxTzWBpFGV6B5IkhiIljEAhow3RLQgMXNAxR+VIRY3AcAh2LLF0ZtXqgAAAAASUVORK5CYII=',
			'900B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIY6IImJTGEMYQhldAhAEgtoZW1ldHR0EEERE2l0bQiEqQM7adrUaStTV0WGZiG5j9UVRR0EQvUimyeAxQ5sbsHm5oEKPypCLO4DAC7eyoUuwIXUAAAAAElFTkSuQmCC',
			'6CC3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WAMYQxlCHUIdkMREprA2OjoEOgQgiQW0iDS4Ngg0iCCLAXmsYBrhvsioaauWrlq1NAvJfSFTUNRB9LZCxETQxNDtwOYWbG4eqPCjIsTiPgAgvs2/jg7FxgAAAABJRU5ErkJggg==',
			'2999' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEHWjSoGcdO0pUszM6OiwpDdF8AY6BASMBVZL6MDQ6NDQ0ADshhrA0ujY0MAih0iDZhuCQ3FdPNAhR8VIRb3AQBvBcueLT3dbgAAAABJRU5ErkJggg==',
			'9D0B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQximMIY6IImJTBFpZQhldAhAEgtoFWl0dHR0EEETc20IhKkDO2na1GkrU1dFhmYhuY/VFUUdBEL1IpsngMUObG7B5uaBCj8qQizuAwDUK8vVOqM0NwAAAABJRU5ErkJggg==',
			'11D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUMDkMRYHRgDWBsdHZDViTqwBrA2BDpg6G0IdHVAct/KrFVRS1dFRkUhuQ+iLqBBBEMvNrFABwyxRocAZPeJhrCGsoYyTHUYBOFHRYjFfQCk6scw9nWbFAAAAABJRU5ErkJggg==',
			'9C19' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMEx1QBITmcLa6BDCEBCAJBbQKtLgGMLoIIImxjAFLgZ20rSp04DEqqgwJPexuoLUMUxF1ssA1gu0C0lMACjmMIUBxQ6wW6agugXkZsZQBxQ3D1T4URFicR8AqQjL0P3S1/0AAAAASUVORK5CYII=',
			'D9EC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHaYGIIkFTGFtZW1gCBBBFmsVaXRtYHRgwSKG7L6opUuXpoauzEJ2X0ArYyCSOqgYQyOmGAumHVjcgs3NAxV+VIRY3AcAnGTMfHh3US8AAAAASUVORK5CYII=',
			'24FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYWllDA0MdkMREpjBMZW1gdAhAEgtoZQgFiYkg625ldEUSg7hp2tKlS0NXZk1Ddl+ASCu6XkYH0VBXNDFWoIno6kSgYshuCQ0Fi6G4eaDCj4oQi/sAxjTJc+Zux9gAAAAASUVORK5CYII=',
			'2F4B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQx0aHUMdkMREpog0MLQ6OgQgiQW0AsWmOjqIIOsGiQXC1UHcNG1q2MrMzNAsZPcFiDSwNqKaxwg0iTU0EMU81gYgrxHVDhGoGLLe0FCwGIqbByr8qAixuA8AmtfLs119M5wAAAAASUVORK5CYII=',
			'1C60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGVqRxVgdWBsdHR2mOiCJiTqINLg2OAQEoOgVaWAFkwj3rcyatmrpVBCJcB9YHdBAEQy9gRhirg0BaHZgcUsIppsHKvyoCLG4DwAL+snBZC+BAQAAAABJRU5ErkJggg==',
			'9524' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WANEQxlCGRoCkMREpog0MDo6NCKLBbSKNLACSTSxECA5JQDJfdOmTl26amVWVBSS+1hdGRodWhkdkPUytALFpjCGhiCJCbSKNDoEoLuFFagTVYw1gDGENTQARWygwo+KEIv7AIpszS4Cgs6qAAAAAElFTkSuQmCC',
			'115E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUMDkMRYHRgDWEEySGKiDqwYYmC9U+FiYCetzFoVtTQzMzQLyX0gdQwNgRh6sYmxYhFjdHREdUsIayhDKCOKmwcq/KgIsbgPAKogxJ5ezRb/AAAAAElFTkSuQmCC',
			'BECE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQxlCHUMDkMQCpog0MDoEOiCrC2gVaWBtEEQVmwISY4SJgZ0UGjU1bOmqlaFZSO5DU4dkHjYxTDvQ3YLNzQMVflSEWNwHAKNlyu0uMEjEAAAAAElFTkSuQmCC',
			'94A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsQ2AMAwEn8I9BewDRXoj4SbThMIbJGyQhimBziSUIPjvTrZ0emxVAv7UV/yIoYgIbFgXkSBYLGOFNOOgV9Y4ChzZ+K0p57x5740fuU4pTIP9hfbiZJLZsFZx3HHpUrHTuWRf7fdgb/x2GevN74twp4IAAAAASUVORK5CYII=',
			'DEB9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQ1lDGaY6IIkFTBFpYG10CAhAFmsFijUEOoigizU6wsTATopaOjVsaeiqqDAk90HUOUzF0NsQ0IBFDNUOLG7B5uaBCj8qQizuAwB7Fc4UsY6kwgAAAABJRU5ErkJggg==',
			'BB1C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQximMEwNQBILmCLSyhDCECCCLNYq0ugYwujAgq5uCqMDsvtCo6aGrZq2MgvZfWjq4OY54BDDtAPVLSA3M4Y6oLh5oMKPihCL+wB8tMyGjf8uwQAAAABJRU5ErkJggg==',
			'5223' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUIdkMQCGlhbGR0dHQJQxEQaXUEkklhgAEOjA1AsAMl9YdNWLV21MmtpFrL7WhmmAHEDsnlAfgBQFMW8gFZGB6AoipjIFNYGRgdGFLewBoiGuoYGoLh5oMKPihCL+wCy68xZwonV0wAAAABJRU5ErkJggg==',
			'5219' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM3QwQ2AIAxA0XJgA9ynbFAT4OA05cAGyAZemNLGgynRo0bp7SVNf4B+eQx/mlf6YjABKqyojNgWCEA0mMs+GHTKZoKM9bQjKbW+9daXpPuKXJAbeleMxFgbFYNiww1XLYsNLZam6CMOzV/934Nz07cDcnzLi2ZSsA8AAAAASUVORK5CYII=',
			'C489' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYWhlCGaY6IImJtDJMZXR0CAhAEgtoZAhlbQh0EEEWa2B0ZXR0hImBnRS1aunSVaGrosKQ3BcANBFo3lRUvaKhriAZVDtaWRsCUOwAuqUV3S3Y3DxQ4UdFiMV9AIGsy8VI1nZ7AAAAAElFTkSuQmCC',
			'84DC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WAMYWllDGaYGIImJTGGYytroECCCJBbQyhDK2hDowIKijtEVJIbsvqVRS5cuXRWZhew+kSkirUjqoOaJhrpiiDG0YtoBFENzCzY3D1T4URFicR8AwHPL6A7UrggAAAAASUVORK5CYII=',
			'8EAC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WANEQxmmMEwNQBITmSLSwBDKECCCJBbQKtLA6OjowIKmjrUh0AHZfUujpoYtXRWZhew+NHVw81hDsYgB1WHaEYDiFpCbgWIobh6o8KMixOI+AEwky7eCxZRCAAAAAElFTkSuQmCC',
			'C7D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WENEQ11DGUIdkMREWhkaXRsdHQKQxAIagWINAQ0iyGINDK2sQDIAyX1Rq1ZNW7oqamkWkvuA8gFI6qBijA6s6OY1sjagi4m0ijSwormFNQQohubmgQo/KkIs7gMA5XzOPWzxqXYAAAAASUVORK5CYII=',
			'57A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMLQiiwU0MDQ6hDJMRRdzdASKIokFBjC0sjYEwPSCnRQ2bdW0pauilqK4r5UhAEkdVIzRgTUUVSwAaBq6OpEpIhhirAFgsdCAQRB+VIRY3AcARIrND+CQAkwAAAAASUVORK5CYII=',
			'A587' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QvRGAMAhGocgGuE9S2FOExhGcAotsoCNYmClNOjgt9ZSve8fPO6BeSuFPecUP4yAgKNmwwKSYopJhtJIGZce4UO59bPymfdur1GM2flxgSSkWe1cEllF5Bb+vM/YsFGzDnmFuzo599b8Hc+N3Ah8EzD/GRYMhAAAAAElFTkSuQmCC',
			'3EED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7RANEQ1lDHUMdkMQCpog0sDYwOgQgq2yFiIkgi01BEQM7aWXU1LCloSuzpiG7bwoWvdjMwyKGzS3Y3DxQ4UdFiMV9AD2UydM9jX70AAAAAElFTkSuQmCC',
			'FC4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZQxkaHUMdkMQCGlgbHVodHQJQxEQaHKY6OoigiTEEwsXATgqNmrZqZWZm1jQk94HUsTZi6mUNDcQQc8BQB3RLI7pbMN08UOFHRYjFfQB6xs4R2T8oJgAAAABJRU5ErkJggg==',
			'0B7E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA0MDkMRYA0RaGRoCHZDViUwRaXRAEwtoBaprdISJgZ0UtXRq2KqlK0OzkNwHVjeFEV1vo0MAI4Ydjg6oYiC3sDagioHd3MCI4uaBCj8qQizuAwCbOcnpK2ceyQAAAABJRU5ErkJggg==',
			'07CE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CHUMDkMRYAxgaHR0CHZDViUxhaHRtEEQRC2hlaGUFmoDsvqilq6YtXbUyNAvJfUB1AUjqoGKMDuhiIlNYG1jR7GANEAGqQnULo4NIAwOamwcq/KgIsbgPALXayUOo7grLAAAAAElFTkSuQmCC',
			'9837' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGUNDkMREprC2sjY6NIggiQW0igBFAtDEWFsZwKII902bujJs1dRVK7OQ3MfqClbXimIzxLwpyGICELEABgy3ODpgcTOK2ECFHxUhFvcBAPCSzIFBX8Q8AAAAAElFTkSuQmCC',
			'C215' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WEMYQximMIYGIImJtLK2MoQwOiCrC2gUaXREF2tgaHSYwujqgOS+qFWrlq6atjIqCsl9QHVTgLBBBFVvAIZYI9D8KYwOIqhuAekOQHYfa4hoqGOow1SHQRB+VIRY3AcA19jLUSB+G3MAAAAASUVORK5CYII=',
			'B614' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYQximMDQEIIkFTGFtZQhhaEQRaxVpBKpsRVUn0gDUOyUAyX2hUdPCVk1bFRWF5L6AKaKtDFMYHdDNc5jCGBqCIYbFLWhiIDczhjqgiA1U+FERYnEfADPpzsQN9U+RAAAAAElFTkSuQmCC',
			'CCE6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1lDHaY6IImJtLI2ujYwBAQgiQU0ijS4NjA6CCCLNYg0sALFkN0XtWraqqWhK1OzkNwHVYdqHlSvCBY7RAi4BZubByr8qAixuA8AKyfMMYPPCHAAAAAASUVORK5CYII=',
			'1DAE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB1EQximMIYGIImxOoi0MoQyOiCrE3UQaXR0dHRA1SvS6NoQCBMDO2ll1rSVqasiQ7OQ3IemDiEWikUMU10rK5qYaIhoCFAMxc0DFX5UhFjcBwCafMjLKc6FTgAAAABJRU5ErkJggg==',
			'3762' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QMQ6AIAxFy9AbwH3K4N5BFk4DAzcAb+BgT2ndSnTUxP6hyU/z/0tBblPgT/qEL3BIlGCQ8bhDjZGY7WWDupRI3nodGur2hu/Isu1DJFu+DoyRKk15jrBwm2g0Da/2icUXpywzszYml9Yf/O9FPfCdNEjMCBOUNugAAAAASUVORK5CYII=',
			'01C3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHUIdkMRYAxgDGB0CHQKQxESmsAawNgg0iCCJBbQyAMWANJL7opaC0KqlWUjuQ1OHIiaCYgcDhh2sAQwYbmF0YA1Fd/NAhR8VIRb3AQBEGMnergNVxAAAAABJRU5ErkJggg==',
			'19B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGaY6IImxOrC2sjY6BAQgiYk6iDS6NgQ6iKDoBYoh1IGdtDJr6dLU0FVTs5DcB7Qj0BXNPEYHBizmsWARw+KWEEw3D1T4URFicR8AESPKiKuGekMAAAAASUVORK5CYII=',
			'C8BE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDGUMDkMREWllbWRsdHZDVBTSKNLo2BKKKNaCoAzspatXKsKWhK0OzkNyHpg4qhsU8LHZgcws2Nw9U+FERYnEfABI5y1VbjFWWAAAAAElFTkSuQmCC',
			'A597' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM3QwQ2AIAxA0fbABnUf2KAHemEDnaIe2EBH8IBTirc2etQoTTi8BPJT2C9H4U/zSh/GQUBQsrHApJiikjFaSIOyM66UT2PTV7Z1a2Npk+njCnPM/TZvRbopL+D/m5MyewsVU4reMPdmZ1/t78G56TsAYSDMborD0zIAAAAASUVORK5CYII=',
			'BAC2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCHaY6IIkFTGEMYXQICAhAFmtlbWVtEHQQQVEn0ugKpEWQ3BcaNW1lKpCOQnIfVF0jih2toqFAsVYGFDGQOoEpDGh2OALdgupmkUaHUMfQkEEQflSEWNwHANhezog7fWeqAAAAAElFTkSuQmCC',
			'8CFB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDA0MdkMREprA2ujYwOgQgiQW0ijSAxERQ1Ik0sCLUgZ20NGraqqWhK0OzkNyHpg5uHiuaedjtwHQL2M0NjChuHqjwoyLE4j4Ad/TLkowAQ+gAAAAASUVORK5CYII=',
			'9D5A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHVqRxUSmiLSyNjBMdUASC2gVaXRtYAgIQBebyuggguS+aVOnrUzNzMyahuQ+VleRRoeGQJg6CGwFi4WGIIkJgO1AVQdyC6OjI4oYyM0MoYyo5g1Q+FERYnEfADfpy/g2mW7eAAAAAElFTkSuQmCC',
			'4FDD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpI37poiGuoYyhjogi4WINLA2OjoEIIkxgsQaAh1EkMRYp6CIgZ00bdrUsKWrIrOmIbkvYAqm3tBQTDEGLOrAYmhuAYuhu3mgwo96EIv7AJ3My9RK68YCAAAAAElFTkSuQmCC',
			'D9F1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA1qRxQKmsLayNjBMRRFrFWl0bWAIxSIG0wt2UtTSpUtTQ1ctRXZfQCtjIJI6qBhDI6YYC6YYxC0oYmA3A90SMAjCj4oQi/sA3LjNgXu0O24AAAAASUVORK5CYII=',
			'FBCD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkNFQxhCHUMdkMQCGkRaGR0CHQJQxRpdGwQdRNDUsTYwwsTATgqNmhq2dNXKrGlI7kNTh2QeNjFMOzDdgunmgQo/KkIs7gMAyHnMuHzNn8EAAAAASUVORK5CYII=',
			'607B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA0MdkMREpjCGMDQEOgQgiQW0sLaCxESQxRpEGh0aHWHqwE6KjJq2MmvpytAsJPeFTAGqm8KIal4rUCyAEdW8VtZWRgdUMZBbWBtQ9YLd3MCI4uaBCj8qQizuAwB+dMtqruuJuQAAAABJRU5ErkJggg==',
			'A212' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsQ3AIAwEn4INyD5mAxemyTRQsAHJBjRMGSuVUVImEv7C0vllv4zxqIyV9Es+R07QcJBhnn2FgNmw0EKJ4igYxhWFms5Mvr2PPk7tJp/6mqrYGymBlVVM+xzdzon5rIRntqWokgX+96Fe8l39xcxKcjIdjQAAAABJRU5ErkJggg==',
			'F602' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQximMEx1QBILaGBtZQhlCAhAERNpZHR0dBBBFWtgBZMI94VGTQtbuioKCBHuC2gQbQWqa3RAM8+1IaCVAU0MaMUUBixuQRUDuZkxNGQQhB8VIRb3AQCslc1vkSh7fAAAAABJRU5ErkJggg==',
			'808D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjCGMDo6OgQgiQW0srayNgQ6iKCoE2l0BKoTQXLf0qhpK7NCV2ZNQ3IfmjqoeSKNrmjmYbcD0y3Y3DxQ4UdFiMV9AFCvyr9yZagTAAAAAElFTkSuQmCC',
			'5ED2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDGaY6IIkFNIg0sDY6BASgizUEOoggiQUGgMRAMgj3hU2bGrZ0VRQQIrmvFayuEdkOqFgrslsCIGJTkMVEpkDcgizGGgByM2NoyCAIPypCLO4DAIHdzRDC4fRjAAAAAElFTkSuQmCC',
			'E8E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHUMDkMQCGlhbWRsYHRhQxEQaXTHEwOpcHZDcFxq1Mmxp6MqoKCT3QdQxNIhgmIdNjNFBBMMOhgBk90Hc7DDVYRCEHxUhFvcBANahzBqI+jMLAAAAAElFTkSuQmCC',
			'578E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGUMDkMSA7EZHR0cHBjQx14ZAFLHAAIZWRoQ6sJPCpq2atip0ZWgWsvtaGQIY0cwD6nRgRTMvoJW1AV1MZIpIA7pe1gCRBgY0Nw9U+FERYnEfAJF1yddjxyvhAAAAAElFTkSuQmCC',
			'4A0E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjAEMExhDA1AFgthDGEIZXRAVscYwtrK6OiIIsY6RaTRtSEQJgZ20rRp01amrooMzUJyXwCqOjAMDRUNRRdjAKpzRLMDJOaA5hawGLqbByr8qAexuA8AdH7KZ9eQqUwAAAAASUVORK5CYII=',
			'0748' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB1EQx0aHaY6IImxBjA0OrQ6BAQgiYlMAYpNdXQQQRILaGVoZQiEqwM7KWrpqmkrM7OmZiG5D6gugLUR1byAVkYH1tBAFPNEprA2MDSi2sEaAOSh6WV0AIuhuHmgwo+KEIv7AP76zLc1RMk1AAAAAElFTkSuQmCC',
			'9478' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsQ2AMAwEP0V6irCPm/SmyBJMYQpvQNggTaYERUKKBSUI/N3Jfp2MehnBn/KKn2eoT5ypY2FFhjBzx1iRIBMFw1zEQudeU9pyKbXUPHd+PgbFCtMHHROxM32DQh1ZdrioF3vbnAXG+av/PZgbvx3iusuqHYcXqwAAAABJRU5ErkJggg==',
			'D0C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCHVqRxQKmMIYwOgRMRRFrZW1lbRAIRRUTaXRtYIDpBTspaum0lamrVi1Fdh+aOjxiYDuwuQVFDOrm0IBBEH5UhFjcBwCZTM1bWr2ynAAAAABJRU5ErkJggg==',
			'3C51' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDHVqRxQKmsDa6NjBMRVHZKtIAFAtFEZsi0sA6lQGmF+yklVHTVi3NzFqK4j6gOqCprejmYRNzRRMDucXREdV9IDcDXRIaMAjCj4oQi/sAcpXMcVnBvwsAAAAASUVORK5CYII=',
			'25FC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA6YGIImJTBFpYG1gCBBBEgtoBYkxOrAg624VCQGJobhv2tSlS0NXZqG4L4Ch0RWhDgyBPAwx1gYRsBiyHUBbW9HdEhrKCLSXAcXNAxV+VIRY3AcAhIvKCAASSuUAAAAASUVORK5CYII=',
			'0978' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxBrC2MjQEBAQgiYlMEWl0aAh0EEESC2gFijU6wNSBnRS1dOnSrKWrpmYhuS+glTHQYQoDinkBrQxAnYwo5olMYWl0dEAVA7mFtQFVL9jNDQwobh6o8KMixOI+AHkXzCpB4fhBAAAAAElFTkSuQmCC',
			'DC28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYQxlCGaY6IIkFTGFtdHR0CAhAFmsVaXBtCHQQQRMDkjB1YCdFLZ22atXKrKlZSO4Dq2tlwDCPYQojhnkOAWhiILc4oOoFuZk1NADFzQMVflSEWNwHAK8LzhbJUA23AAAAAElFTkSuQmCC',
			'4AA4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpI37pjAEAHFDALJYCGMIQyhDI7IYYwhrK6OjQyuyGOsUkUbXhoApAUjumzZt2srUVVFRUUjuCwCrC3RA1hsaKhrqGhoYGoLiFrB5qG4hVmygwo96EIv7ACotzziYF/e3AAAAAElFTkSuQmCC',
			'B1AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYAhimMEwNQBILmMIYwBDKECCCLNbKGsDo6OjAgqKOIYC1IdAB2X2hUauilq6KzEJ2H5o6qHlAsVAsYkB1mHYEoLglFKgTKIbi5oEKPypCLO4DAIDGyxCvdI+JAAAAAElFTkSuQmCC',
			'0881' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGVqRxVgDWFsZHR2mIouJTBFpdG0ICEUWC2gFq4PpBTspaunKsFWhq5Yiuw9NHVQMbF4rFjuwuQVFDOrm0IBBEH5UhFjcBwAR4strtExGUwAAAABJRU5ErkJggg==',
			'66EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUMDkMREprC2sjYwOiCrC2gRacQQaxBpQBIDOykyalrY0tCVoVlI7guZIoppXqtIoysRYtjcgs3NAxV+VIRY3AcA0nnJkeWm/9oAAAAASUVORK5CYII=',
			'8FDB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DGUMdkMREpog0sDY6OgQgiQW0AsUaAh1E0NUBxQKQ3Lc0amrY0lWRoVlI7kNTh9M8nHaguYU1ACiG5uaBCj8qQizuAwCcDsyH6RQ35AAAAABJRU5ErkJggg==',
			'093D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGUMdkMRYA1hbWRsdHQKQxESmiDQ6NAQ6iCCJBbQCxYDqRJDcF7V06dKsqSuzpiG5L6CVMRBJHVSMAcM8kSksGGLY3ILNzQMVflSEWNwHAMAby9gLRdjSAAAAAElFTkSuQmCC',
			'ABD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGUMDkMRYA0RaWRsdHZDViUwRaXRtCEQRC2gFqmsIdHVAcl/U0qlhS1dFRkUhuQ+iLqBBBElvaCjIPFQxoDqwHSLodjQ6BASgiIHczDDVYRCEHxUhFvcBAA7rzWAsa98jAAAAAElFTkSuQmCC',
			'AA93' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUIdkMRYAxhDGB0dHQKQxESmsLayNgQ0iCCJBbSKNLoCxQKQ3Be1dNrKzMyopVlI7gOpcwiBqwPD0FBRoJ2Y5jliE0NzC9g8NDcPVPhREWJxHwDY+c4cp3Px/wAAAABJRU5ErkJggg==',
			'CC28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYQxlCGaY6IImJtLI2Ojo6BAQgiQU0ijS4NgQ6iCCLNYB4ATB1YCdFrZq2atXKrKlZSO4Dq2tlQDUPJDaFEdU8oB0OAahiYLc4oOoFuZk1NADFzQMVflSEWNwHABqIzNb3MBZTAAAAAElFTkSuQmCC',
			'CDC7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WENEQxhCHUNDkMREWkVaGR0CGkSQxAIaRRpdGwRQxRpAYiAa4b6oVdNWpq5atTILyX1Qda0MmHqnMGDaEcCA4ZZAByxuRhEbqPCjIsTiPgCVxc0PNUL/5QAAAABJRU5ErkJggg==',
			'DB31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQxhDGVqRxQKmiLSyNjpMRRFrFWl0aAgIRRNrZWh0gOkFOylq6dSwVVNXLUV2H5o6ZPMIi0HcgiIGdXNowCAIPypCLO4DAN2azzRCPNVyAAAAAElFTkSuQmCC',
			'CC26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WEMYQxlCGaY6IImJtLI2Ojo6BAQgiQU0ijS4NgQ6CCCLNYgAyUAHZPdFrZq2atXKzNQsJPeB1bUyopoHEpvC6CCCZodDAKoY2C0ODCh6QW5mDQ1AcfNAhR8VIRb3AQBW6cxDidVXlgAAAABJRU5ErkJggg==',
			'3BA7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RANEQximMIaGIIkFTBFpZQhlaBBBVtkq0ujo6IAqBlTH2hAAhAj3rYyaGrZ0VdTKLGT3QdS1MqCZ5xoaMAVDrCEggAHNLawNgQ7obkYXG6jwoyLE4j4A31PMvrQX3KoAAAAASUVORK5CYII=',
			'EEAF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMIaGIIkFNIg0MIQyOjCgiTE6OmKIsTYEwsTATgqNmhq2dFVkaBaS+9DUIcRCsYhhU4cmBnIzuthAhR8VIRb3AQC4RMseHJcSewAAAABJRU5ErkJggg==',
			'630B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANYQximMIY6IImJTBFpZQhldAhAEgtoYWh0dHR0EEEWa2BoZW0IhKkDOykyalXY0lWRoVlI7guZgqIOoreVodEVKCaCJoZuBza3YHPzQIUfFSEW9wEAUpDLisFHK50AAAAASUVORK5CYII=',
			'A62C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaYGIImxBrC2Mjo6BIggiYlMEWlkbQh0YEESC2gFqQh0QHZf1NJpYatWZmYhuy+gVbSVoZXRAdne0FCRRocpqGJA8xodAhjR7GAF6URxS0ArYwhraACKmwcq/KgIsbgPAEnLyvE3EtZ6AAAAAElFTkSuQmCC',
			'2D65' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUMDkMREpoi0Mjo6OiCrC2gVaXRtQBVjAIsxujogu2/atJWpU1dGRSG7LwCoztGhQQRJL6MDSG8AihhrA0gs0AFZTKQB5BaHAGT3hYaC3Mww1WEQhB8VIRb3AQBlNsvBsWx2ggAAAABJRU5ErkJggg==',
			'096E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMDkMRYA1hbGR0dHZDViUwRaXRtQBULaAWJMcLEwE6KWrp0aerUlaFZSO4LaGUMdHVE18sA1BuIZgcLhhg2t2Bz80CFHxUhFvcBAAR5yazKEQAHAAAAAElFTkSuQmCC',
			'FBBC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1hDGaYGIIkFNIi0sjY6BIigijW6NgQ6sGCoc3RAdl9o1NSwpaErs5Ddh6YOxTxsYph2oLsF080DFX5UhFjcBwDp082lMqZwpQAAAABJRU5ErkJggg==',
			'834F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WANYQxgaHUNDkMREpoi0MrQ6OiCrC2hlaHSYiiomMoWhlSEQLgZ20tKoVWErMzNDs5DcB1LH2ohpnmtoIKYdjeh2AN2CJgZ1M4rYQIUfFSEW9wEAj/HKzwEjiZQAAAAASUVORK5CYII=',
			'2F4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQx0aHUMdkMREpog0MLQ6OgQgiQW0AsWmOjqIIOsGiQXCxSBumjY1bGVmZtY0ZPcFiDSwNqLqZQTyWEMDUcRYG4A8NHUiUDFkt4SGgsVQ3DxQ4UdFiMV9AFTiy3wA/1SIAAAAAElFTkSuQmCC',
			'C209' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WEMYQximMEx1QBITaWVtZQhlCAhAEgtoFGl0dHR0EEEWa2BodG0IhImBnRS1atXSpauiosKQ3AdUN4W1IWAqmt4AoFgDilgjowOjowOKHUC3NKC7hTVENNQBzc0DFX5UhFjcBwAtMcwwaIPzqAAAAABJRU5ErkJggg==',
			'E120' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGVqRxQIaGAMYHR2mOqCIsQawNgQEBKCIAfU2BDqIILkvNGpV1KqVmVnTkNwHVtfKCFOHEJuCRQwI0e1gdGBAcUtoCGsoa2gAipsHKvyoCLG4DwD8bspf3HrSFwAAAABJRU5ErkJggg=='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>