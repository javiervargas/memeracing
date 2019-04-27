<?php
$home = 1;
$notMailed = "";
$content = '';
session_start();
include('queries.php');
include('controller.php');
$path = explode("/",$_SERVER['REQUEST_URI']);
if($path[0] == '')
{
    array_shift($path);
}
$fn = 'bwb_'.preg_replace("/\?.*/",'',$path[$fnElemNum]);
//die(print_r($path,true));
if(function_exists($fn))
{
    while($fnElemNum-- > 0)
    {
        array_shift($path);
    }
    try
    {
        $content = call_user_func($fn);
    }
    catch( Exception $e )
    {
        $err = print_r($e,true);
        errLog($err);
        if(preg_match('/^127\.0\./', $_SERVER['SERVER_ADDR'])  || $_SERVER['SERVER_ADDR'] == "::1")
        {
            $content = $err;
        }
    }
}
else
{
    // $content = bwb_prompts();
}
if(isset($_SESSION['id']) && $_SESSION['id'] > '')
{
    preg_match('/^(?P<name>[^@]+)@/',$_SESSION['email'],$n);
    $name = $n['name'];
    $logout = "<li><a href='{$siteURL}logout'>$name Log out</a></li>";
}
else
{
    $logout = '';
    $name = '';
}
include('header.php');
?>

      <div class="hero">
        <div class="container">
          <?php if(!function_exists($fn))
          {
          	bwb_hero();
          } ?>
        </div>
      </div>

      <div class="mr-field">
        <div id="main" class="container">
          <div id="content">
<?php if( $_SERVER['SERVER_ADDR'] == '::1x' ) {
    $randTx = md5(time()).'b3eafb09757bcf7bb6dcc2e488b2e368';
?>
            <form action='<?php echo $siteURL; ?>received' target='BCT' onsubmit='location.href="";'>
                <input name='value' value='1001002'/>
                <input type='hidden' name='transaction_hash' value='<?php echo $randTx; ?>' />
                <input type='hidden' name='input_address' value='1CyuAfo4r6KzspipMoXkN8ReaKf797QrPW'/>
                <input type='hidden' name='confirmations' value='0'/>
                <input type='hidden' name='secret' value='2e5389693117e2634'/>
                <input type='submit' value='Test Bet' class='btn' />
            </form><br/>
            <a href='<?php echo $siteURL; ?>reset'>Reset</a> |
<?php } ?>
            <?php if($fn != 'bwb_search') echo bwb_search(); ?>

            <?php echo $content; ?>

          </div> <!--end #content-->
        </div> <!--end #main (check)-->
      </div>

<?php include('footer.php'); ?>
