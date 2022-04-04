<?php

use Phppot\DataSource;

require_once 'DataSource.php';
$db = new DataSource();
$conn = $db->getConnection();

if (isset($_POST["import"])) {

    $fileName = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {

        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

            $invoiceNo = "";
            if (isset($column[0])) {
                $invoiceNo = mysqli_real_escape_string($conn, $column[0]);
            }
            $stockCode = "";
            if (isset($column[1])) {
                $stockCode = mysqli_real_escape_string($conn, $column[1]);
            }
            $quantity = "";
            if (isset($column[2])) {
                $quantity = mysqli_real_escape_string($conn, $column[2]);
            }
            $invoiceDate = "";
            if (isset($column[3])) {
                $invoiceDate = mysqli_real_escape_string($conn, $column[3]);
            }
            $unitPrice = "";
            if (isset($column[4])) {
                $unitPrice = mysqli_real_escape_string($conn, $column[4]);
            }
            $customerId = "";
            if (isset($column[4])) {
                $customerId = mysqli_real_escape_string($conn, $column[4]);
            }
            $country = "";
            if (isset($column[4])) {
                $country = mysqli_real_escape_string($conn, $column[4]);
            }

            $sqlInsert = "INSERT into csv_data (invoiceNo,stockCode,quantity,invoiceDate,unitPrice,customerId,country)
                   values (?,?,?,?,?,?,?)";
            $paramType = "issss";
            $paramArray = array(
                $invoiceNo,
                $stockCode,
                $quantity,
                $invoiceDate,
                $unitPrice,
                $customerId,
                $country
            );
            $insertId = $db->insert($sqlInsert, $paramType, $paramArray);

            if (!empty($insertId)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.2.1.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <style>
        body {
            font-family: Arial;
            width: 550px;
        }

        .outer-scontainer {
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 20px;
            border-radius: 2px;
        }

        .input-row {
            margin-top: 0px;
            margin-bottom: 20px;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 100px;
            border-radius: 2px;
            cursor: pointer;
        }

        .outer-scontainer table {
            border-collapse: collapse;
            width: 100%;
        }

        .outer-scontainer th {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .outer-scontainer td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        #response {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 2px;
            display: none;
        }

        .success {
            background: #c7efd9;
            border: #bbe2cd 1px solid;
        }

        .error {
            background: #fbcfcf;
            border: #f3c6c7 1px solid;
        }

        div#response.display-block {
            display: block;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#frmCSVImport").on("submit", function() {

                $("#response").attr("class", "");
                $("#response").html("");
                var fileType = ".csv";
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                if (!regex.test($("#file").val().toLowerCase())) {
                    $("#response").addClass("error");
                    $("#response").addClass("display-block");
                    $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                    return false;
                }
                return true;
            });
        });
    </script>
</head>

<body>
    <h2>Import CSV file</h2>

    <div id="response" class="<?php if (!empty($type)) {
                                    echo $type . " display-block";
                                } ?>">
        <?php if (!empty($message)) {
            echo $message;
        } ?>
    </div>
    <div class="container">

            <form action="" method="post" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Upload File</label>
                    <input type="file" name="file" id="file" accept=".csv">
                </div>
                <button type="submit" id="submit" name="import" class="btn-submit btn btn-primary">Import</button>
            </form>

        </div>
        <?php
        $sqlSelect = "SELECT * FROM csv_data";
        $result = $db->select($sqlSelect);
        if (!empty($result)) {
        ?>
            <table id='userTable'>
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Stock code</th>
                        <th>Quantity</th>
                        <th>Invoice Date</th>
                        <th>Unit Price</th>
                        <th>Customer Id</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <?php

                foreach ($result as $row) {
                ?>

                    <tbody>
                        <tr>
                            <td><?php echo $row['invoiceNo']; ?></td>
                            <td><?php echo $row['stockCode']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['invoiceDate']; ?></td>
                            <td><?php echo $row['unitPrice']; ?></td>
                            <td><?php echo $row['customerId']; ?></td>
                            <td><?php echo $row['country']; ?></td>
                        </tr>
                    <?php
                }
                    ?>
                    </tbody>
            </table>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
