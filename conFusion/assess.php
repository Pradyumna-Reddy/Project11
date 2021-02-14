<?php

    // connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="coolcss.css">
    </head>
    <body>
        <header>

        </header>
        <main>
            <div id="form_div">
                <form id="assess_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" >
                    <div class="assess_fields">
                        <label for="roll">
                            Rollno
                        </label>
                        <input type="text" id="roll" name="roll" required>
                    </div>
                    <div class="assess_fields">
                        <label for="obs">
                            Observation
                        </label>
                        <input type="number" id="obs" name="obs" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="CoE">
                            Completion of Experiment
                        </label>
                        <input type="number" id="CoE" name="CoE" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="viva">
                            Viva
                        </label>
                        <input type="number" id="viva" name="viva" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="rec">
                            Record
                        </label>
                        <input type="text" id="rec" name="rec" value="0" required>
                    </div>
                    <div id="upload_buttons">
                    <input type="submit" name="submit" value="Submit" class="button_color button shadow" >
                    </div>
                </form>
            </div>
            <div>
                <?php

                    //update data

                    if(isset($_POST['submit'])){
                        //check if roll no is right?
                        $req_stmt = $conn->prepare("SELECT * from assessment WHERE hallticket=?");
                        $req_stmt->bind_param("s",$_POST['roll']);
                        $req_stmt->execute();
                        $res = $req_stmt->get_result();
                        $row = $res->fetch_assoc();
                        $marks = $row['Marks'];
                        $weeks_done = $row['no_of_assess'];
                        $total = $marks * $weeks_done;
                        $this_week = $_POST['obs'] + $_POST['CoE'] + $_POST['viva'] + $_POST['rec'];
                        $total = $total + $this_week;
                        $weeks_done++;
                        $total = $total/$weeks_done;
                        $req_stmt->close();

                        $upd_stmt = $conn->prepare("UPDATE assessment SET Marks = ?, no_of_assess = ?, this_week = ? WHERE hallticket=?");
                        $upd_stmt->bind_param('ssss',$total, $weeks_done, $this_week, $_POST['roll']);
                        $upd_stmt->execute();
                        $upd_stmt->close();

                        ?>
                        <div class="fade-out">
                            <h3>Data submitted succesfully</h3>
                        </div>
                        <?php

                    }

                ?>
            </div>
            <div class="table_div">
                <table>
                    <tr>
                        <th>Rollno</th>
                        <th>Cummulative Marks</th>
                    </tr>
                <?php
                    $dis_stmt = $conn->prepare("SELECT * from assessment");
                    $dis_stmt->execute();
                    $dis = $dis_stmt->get_result();
                    while($erow = $dis->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $erow['hallticket'];?></td>
                            <td><?php echo $erow['Marks'];?></td>
                        </tr>
                    <?php }
                ?>
                </table>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>