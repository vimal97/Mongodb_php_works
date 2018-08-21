<!DOCTYPE html>
<html>
    <head>
        <title>Mongodb operations</title>
        <style>
        input[type=text] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        input[type=button], input[type=submit], input[type=reset] {
            background-color: rgb(175, 76, 76);
            border: none;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
        }
        </style>
        </head>
    <body>
            <form action="index1.php" method="post" id='db_info'>
            <p style="text-align:center;margin-top:7%;" id='db_create'>
                <label for="fname1">Database Name</label>
                <input type="text" id="fname1" name="db_name">
                <input type="submit" id="create" value="Create Database using Mongodb" > <input type="submit" id="used" value="Use existing database" ></p>
            </form>
            <form id="forms" action="index1.php" method="post">

            </form>
        <form id="forms1" action="index1.php" method="post">

        </form>
    </body>
</html>



<?php
    if(isset($_POST['db_name']))
    {
        $db_name=$_POST['db_name'];
        $string1='<label for="fname">First Name</label><input type="text" id="fname" name="update_fname"><label for="lname">Last Name</label><input type="text" id="lname" name="update_lname"><select name="mode"><option value="insert">Insert</option><option value="update">Update</option><option value="delete">Delete</option></select><p style="text-align:center;"><input type="submit" id="insert" value="Submit"></p>';
        echo "<script>alert('creating database $db_name');
        document.getElementById('db_info').innerHTML='';
        document.getElementById('forms').innerHTML='".$string1."'</script>";
        $mng = new MongoDB\Driver\Manager(); // Driver Object created
        $bulk = new MongoDB\Driver\BulkWrite;
    }
    if(isset($_POST['mode']))
    {
        $mode=$_POST['mode'];
        if($mode=="insert")
        {
                $mng = new MongoDB\Driver\Manager(); // Driver Object created
        $bulk = new MongoDB\Driver\BulkWrite;
                $db_name=$_POST['db_name'];
            $fname=$_POST['update_fname'];
            $lname=$_POST['update_lname'];
            $doc = ["_id" => new MongoDB\BSON\ObjectID, "fname" => "$fname", "lname" => "$lname"];
            $bulk->insert($doc);
            $mng->executeBulkWrite("names.collection1", $bulk);
            echo "<script>alert('Successfully inserted')</script>";
        }
        elseif($mode=="update")
        {
                $mng = new MongoDB\Driver\Manager(); // Driver Object created
        $bulk = new MongoDB\Driver\BulkWrite;
            $old_fname=$_POST['update_fname'];
            $old_lname=$_POST['update_lname'];
            session_start();
            $_SESSION['update_fname']=$old_fname;
            $_SESSION['update_lname']=$old_lname;
            $string2='<label for="fname">First Name</label><input type="text" id="fname" name="fname"><label for="lname">Last Name</label><input type="text" id="lname" name="lname"><p style="text-align:center;"><input type="submit" id="insert" value="Update"></p>';
            echo "<script>document.getElementById('db_info').innerHTML='';document.getElementById('forms1').innerHTML='".$string2."'</script>";
            $query = new MongoDB\Driver\Query([]);
             $rows = $mng->executeQuery("names.collection1", $query);
            foreach ($rows as $row)
            {
              if(($row->fname=="$old_fname") and ($row->lname=="$old_lname"))
              {
                echo "old name found";
              }
            }
        }
        elseif($mode=="delete")
        {
            $mng = new MongoDB\Driver\Manager(); // Driver Object created
        $bulk = new MongoDB\Driver\BulkWrite;

            $fname=$_POST['update_fname'];
            $lname=$_POST['update_lname'];
             $bulk->delete(
                ['fname' => "$fname",'lname' => "$lname"]
        );

        $result = $mng->executeBulkWrite('names.collection1', $bulk);
            echo "<script>alert('hi')</script>";
        }
        else
            ;
    }
    if(isset($_POST['fname']))
    {
        session_start();
        $mng = new MongoDB\Driver\Manager(); // Driver Object created
        $bulk = new MongoDB\Driver\BulkWrite;
        $new_fname=$_POST['fname'];
        $new_lname=$_POST['lname'];
        $old_fname=$_SESSION['update_fname'];
        $old_lname=$_SESSION['update_lname'];
        $bulk->update(
                ['fname' => "$old_fname",'lname' => "$old_lname"],
                ['$set' => ['fname' => "$new_fname",'lname' => "$new_lname"]],
                ['multi' => false, 'upsert' => false]
        );

        $result = $mng->executeBulkWrite('names.collection1', $bulk);
    }
?>
