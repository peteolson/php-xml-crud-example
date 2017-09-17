<?php 
if (!isset($id) || $id == "") :
include('header.php');

?>
<h3>Add New Record</h3>
<form method="POST" action="process.php?add">
    <table>
        
        <tr>
            <td>Name</td>
            <td><input type='text' name='name'/></td>
        </tr>
       
        <tr>
            <td>Notes</td>

            <td><input type='text' name='notes'/></td>
        </tr>
    </table>
    <input type="submit" value="Save"/>
</form>
<?php 
include('footer.php');
else:
?>

    <h3>Update:</h3>
    <form method="POST" action="process.php?update">
        <table>
            <tr>

                <td><input type='hidden' name='id' value='<?php echo $user->attributes()->id;?>'/></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value='<?php echo $user->name;?>'/></td>
            </tr>
           
            <tr>
                <td>Notes</td>

                <td><input type='text' name='notes' value='<?php echo $user->notes;?>'/></td>
            </tr>
        </table>
        <input type="submit" value="Save"/>
    </form>
    <form method="POST" action="process.php?delete">
	<input type='hidden' name='id' value='<?php echo $user->attributes()->id;?>'/>
	<input type="submit" value="Delete"/>
    </form>

<?php 
endif;
?>