<?php  
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php');
    } if (isset($_POST['user_id'])) {
        if ($_SESSION['user_id'] != $_POST['user_id']) {
            var_dump($_GET['ID']);
            exit();
        }
    } elseif (isset($_GET['ID'])) {
        if ($_SESSION['user_id'] != $_GET['ID']) {
            header('location: profile.php');
            exit();
        }
    } else {
        header('location: profile.php');
        exit();
    }

    require_once 'includeFiles/profileQuery.inc.php';
    require_once 'includeFiles/functions/main.function.php';
    $user = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $userName. ' | módosítás' ?></title><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
		
		<style>

		.image_area {
		  position: relative;
		}

		img {
		  	display: block;
		  	max-width: 100%;
		}

		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
		
		</style>
	</head>
	<body>
		<div class="container" align="center">
			<br />
			<h3 align="center">Profil módosítása</h3>
			<br />
			<div class="row">
				<div class="col-md-4">&nbsp;</div>
				<div class="col-md-4">
					<div class="image_area">
						<form method="post">
							<label for="upload_image">
								<img src="images/profileImage/profile.<?php echo 'default'; //echo $_SESSION['user_id'] ?>.png" id="uploaded_image" class="img-responsive img-circle" />
								<div class="overlay">
									<div class="text">Click to Change Profile Image</div>
								</div>
								<input type="file" name="image" class="image" id="upload_image" style="display:none" />
							</label>
						</form>
					</div>
			    </div>
    		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title">Crop Image Before Upload</h5>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          			<span aria-hidden="true">×</span>
			        		</button>
			      		</div>
			      		<div class="modal-body">
			        		<div class="img-container">
			            		<div class="row">
			                		<div class="col-md-8">
			                    		<img src="" id="sample_image" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop" class="btn btn-primary">Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
		</div>
		<div class="container" align="center">
			<form action="includeFiles/profileEdit.inc.php" method="post">
				<input type="text" name="username_reset" id="username_reset" value="<?php echo $userName ?>">
				<input type="hidden" name="username" id="username" value="<?php echo $userName ?>">
				<br />

				<input type="text" name="email_reset" id="email_reset" value="<?php echo $userEmail ?>">
				<br />


				<input type="password" name="password_old" id="password_old" placeholder="Old password">
				<br />
				<input type="password" name="password_reset" id="password_reset" placeholder="New password">
				<br />
				<input type="password" name="password2_reset" id="password2_reset" placeholder="New password again">
				<br />


				<input type="text" name="firstname_reset" id="firstname_reset" value="<?php echo $userFirstName ?>">
				<input type="text" name="lastname_reset" id="lastname_reset" value="<?php echo $userLastName ?>">
				<br />

					
				<button type="submit" id="profile_reset_submit" name="profile_reset_submit">név módosítása</button>
			</form> 
		</div>
	</body>
</html>

<script>

$(document).ready(function(){

	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'includeFiles/profileEdit.inc.php',
					method:'POST',
					data:{image:base64data},
					success:function(data)
					{
						window.location.href = "profile.php?", Math.random();
						$modal.modal('hide');
						$('#uploaded_image').attr('src', data);
					}
				});
			};
		});
	});
	
});
</script>
</html>