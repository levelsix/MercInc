<?php
//include_once('topmenu.php');
include_once('properties/serverproperties.php');

    if(isset($_GET['UUID']) && trim($_GET['UUID']) != ""){
        header("location: backend/charcreate.php?UUID=".$_GET['UUID']);
        exit;
    }

?>

<form action="<?php echo $serverRoot; ?>backend/charcreate.php" onsubmit="return validateChar();" method="post">
Greetings, young warrior. What will you choose as your mercenary alias?
<br>
<input type="text" name="charname" id="charname"/>
<br><br>

<input type="hidden" name="justmadechar" value="true"> 
<input type="submit" value="Finish!" />
</form>

</body>

<script>
 function validateChar() {
   var charname = document.getElementById('charname').value;

  if (charname.trim() == '') {
    alert('The character needs a name to proceed.');
    return false;
  }

//check to see if user already has char with this name


   return true;
 }
</script>

<?php
include_once 'footer.php';
?>

