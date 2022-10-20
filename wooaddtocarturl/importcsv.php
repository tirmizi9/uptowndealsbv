<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<style>
.heading {
	text-align: center;
}

.form-container, #frmCSVImport {
	border: #e0dfdf 1px solid;
	padding: 30px 30px 10px 30px;
	border-radius: 15px;
	margin: 10px auto;
	width: 350px;
	text-align: center;
}

.input-row {
	margin-top: 0px;
	margin-bottom: 20px;
}

.btn-submit {
	background: #efefef;
	border: #d3d3d3 1px solid;
	width: 100%;
	border-radius: 20px;
	cursor: pointer;
	padding: 12px;
}

.btn-submit:hover {
	background: #d9d8d8;
	border: #c3c1c1 1px solid;
}

.outer-container table {
	border-collapse: collapse;
	width: 100%;
}

.outer-container th {
	border-top: 2px solid #dddddd;
	background: #f9f9f9;
	padding: 8px;
	text-align: left;
	font-weight: normal;
}

.outer-container td {
	border-top: 1px solid #dddddd;
	padding: 8px;
	text-align: left;
}

.outer-container label {
	margin-bottom: 5px;
	display: inline-block;
}

#response {
	padding: 10px;
	border-radius: 15px;
}

.success {
	background: #c7efd9;
	border: #bbe2cd 1px solid;
}

.error {
	background: #fbcfcf;
	border: #f3c6c7 1px solid;
}

.file {
	border: 1px solid #cfcdcd;
	padding: 10px;
	border-radius: 20px;
	color: #171919;
	width: 100%;
	margin-bottom: 20px;
}
table {
            border-collapse: collapse;
            width: 100%;
        }
          
        th, td {
            text-align: left;
            padding: 8px;
        }
 tr:nth-child(odd) {
            background-color: #d9e4e8;
        }
		thead tr, thead tr th{background-color: #fff ; } 
		tbody tr td.pname{
			width: 180px;  
			 white-space: nowrap;  
			 overflow: hidden !important;  
			 text-overflow: ellipsis;
		}
</style>
<?php
if (isset($_POST["import"])) {
    $fileName = $_FILES["peoductList"]["tmp_name"];
	$domainname = $_POST['domainname'];
        if ($_FILES["peoductList"]["size"] > 0) {
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/xlsx', 'application/vnd.msexcel', 'text/plain');
					$tp = $_FILES['peoductList']['type'] ;
					$name = $_FILES["peoductList"]["name"];
					$tmp = explode('.', $name);
					$ext = end($tmp);
					echo  '<!--'.$tp .', '. $ext .'-->';
					
					// Validate whether selected file is a CSV file
					if(!empty($_FILES['peoductList']['name']) && in_array($_FILES['peoductList']['type'], $csvMimes)){
						
						// If the file is uploaded
						if(is_uploaded_file($_FILES['peoductList']['tmp_name'])){
							
							// Open uploaded CSV file with read-only mode
							$csvFile = fopen($_FILES['peoductList']['tmp_name'], 'r');
							
							// Skip the first line
							fgetcsv($csvFile);
							echo '<table id="userTable">
									<thead>
										<tr>
											<th>Product Id</th>
											<th>Product Name</th>
											<th>Product Url</th>
											<th>Copy URL to clipboard</th>
										</tr>
									</thead><tbody>';
							// Parse data from CSV file line by line
							while(($line = fgetcsv($csvFile)) !== FALSE){
								// Get row data
									$id   = @$line[0];
									$name  = @$line[1];
									$url = $domainname. 'checkout/?add-to-cart=' .$id .'&quantity=1&extpdt=';
									echo '<tr><td>' .$id. '</td>
									<td class="pname"><a title="'.$name.'" href="javascript:void(0);">' .$name. '</a></td>
									<td><b id="mjt_'.$id.'">' . $url . '</b></td>
									<td><button class="button button-small copy-attachment-url" data-clipboard-target="#mjt_'.$id.'">Copy URL to clipboard</button></td>
									</tr>';
							}
							echo '</tbody></table>';
							// Close opened CSV file
				fclose($csvFile);							
				$qstring = '<b style="color:green">Status => Success</b>';
			}
        }
	}
}
?>
<form action="" method="post" name="frmCSVImport" id="frmCSVImport"
	enctype="multipart/form-data" onsubmit="return validateFile()">
	<div Class="input-row">
	<label>Domain Url </label> <input type="text" name="domainname" id="domainname"
			class="file" value="<?php echo get_bloginfo('url'); ?>/">
		<label>Coose your file. </label> <input type="file" name="peoductList" id="file"
			class="file" accept=".csv,.xls,.xlsx">
		<div class="import">
			<button type="submit" id="submit" name="import" class="btn-submit">Import
				CSV and Save Data</button>
		</div>
	</div>
</form>

<script type="text/javascript">
function validateFile() {
    var csvInputFile = document.forms["frmCSVImport"]["file"].value;
    if (csvInputFile == "") {
      error = "No source found to import. Please choose a CSV file. ";
      $("#response").html(error).addClass("error");;
      return false;
    }
    return true;
  }
</script>
<script>
      var btns = document.querySelectorAll('button');
      var clipboard = new ClipboardJS(btns);

      clipboard.on('success', function (e) {
        console.log(e);
      });

      clipboard.on('error', function (e) {
        console.log(e);
      });
    </script>