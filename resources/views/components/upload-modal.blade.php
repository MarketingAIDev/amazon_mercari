<style>
.modal-backdrop
{
    opacity:0.5 !important;
}
</style>

<div id="uploadModal" class="modal fade in" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h4 class="modal-title text-white">画像アップロード</h4>
        		<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-12 p-0">
							<img id="crop-img" src="" style="max-width: 600px !important;" />
						</div>
					</div>
					<div class="row">
						<div class="col-8">
							<input id="upload-input" class="form-control" type="file" name="image">
						</div>
						<div class="col-4">
							<!-- <span class="input-group-text">画像ファイルを選択</span> -->
							<span type="button" class="btn btn-danger btn-block" data-bs-dismiss="modal" id="cropImg">
								<i class="fa fa-upload" aria-hidden="true"></i>&nbsp;トリミング &amp; 保存
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	let cropper;
	let image = null;

	$("body").on("change", "#upload-input", function (e) {
		image = document.getElementById('crop-img');

		let done = function(url) {
			if (cropper !== undefined) {
				cropper.destroy();
				cropper = null;
			}

			image.src = url;
			cropper = new Cropper(image, {
				aspectRatio				: 9 / 4,
				minContainerHeight: 320,
				minContainerWidth	: 720,
			});
		};

		let files = e.target.files;
		let reader, file, url;
		if (files && files.length > 0) {
			file = files[0];
			if (URL) {
				done(URL.createObjectURL(file));
			} else if (FileReader) {
				reader = new FileReader();
				reader.onload = function(e) {
					done(reader.result);
				};
				reader.readAsDataURL(file);
			}
		}
	});

	$("#cropImg").click(function() {
		let canvas = cropper.getCroppedCanvas({
			width: 900,
			height: 400,
		});
		canvas.toBlob(function(blob) {
			url = URL.createObjectURL(blob);
			let reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function() {

				let obj = $("[data-triggered = true]").attr("data-object");
				let postData = {
					image: reader.result,
					target: obj,
				};

				let targetObj = obj.replace(/\d+/, '');
				let targetId = obj.match(/\d+/)[0];

				if (targetObj == "T") {
					$("#template_cover").css("background-image", 'url(' + reader.result + ')');
				} else if (targetObj == "TQ") {
					$("#question_cover").css("background-image", 'url(' + reader.result + ')');
				} else if (targetObj == "TR") {
					$("#result_cover").css("background-image", 'url(' + reader.result + ')');
				} else if (targetObj == "Q") {
					$("#quiz_cover").css("background-image", 'url(' + reader.result + ')');
				} else if (targetObj == "QQ") {
					$("#question_cover").css("background-image", 'url(' + reader.result + ')');
				} else if (targetObj == "QR") {
					$("#result_cover").css("background-image", 'url(' + reader.result + ')');
				}

				$.ajax({
					url:"{{url('save-crop-image')}}",  
					type:'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
					},
					data: {
						'image': JSON.stringify(postData)
					},
					success:function(result, status, xhr) {
						$('#uploadModal').modal('hide');
						console.log(result);
						if (targetObj == "T") {
							template.image_id = result.img.id;
							template.image.url = result.img.url;
						} else if (targetObj == "TQ") {
							for (let q of template.questions) {
								if (q.id == targetId) {
									q.image_id = result.img.id;
									q.image.url = result.img.url;
								}
							}
						} else if (targetObj == "TR") {
							for (let r of template.results) {
								if (r.id == targetId) {
									r.image_id = result.img.id;
									r.image.url = result.img.url;
								}
							}
						} else if (targetObj == "Q") {
							template.image_id = result.img.id;
							template.image.url = result.img.url;
						} else if (targetObj == "QQ") {
							for (let q of template.questions) {
								if (q.id == targetId) {
									if (q.image == null) {
										q.image = {};
									}
									q.image_id = result.img.id;
									q.image.url = result.img.url;
								}
							}
						} else if (targetObj == "QR") {
							for (let r of template.results) {
								if (r.id == targetId) {
									if (r.image == null) {
										r.image = {};
									}
									r.image_id = result.img.id;
									r.image.url = result.img.url;
								}
							}
						}
					}
				});
			}
		});
	});

	$("#uploadModal").on('shown.bs.modal', function() {
		let url = $("[data-triggered = true]")[0].style.backgroundImage.replace('url("', '').replace('")', '');

		if (url == "") {
			let obj = $("[data-triggered = true]").parents()[1].nextElementSibling;
			url = obj.style.backgroundImage.replace('url("', '').replace('")', '');
		}

		image = document.getElementById('crop-img');
		image.src = url;
		
		cropper = new Cropper(image, {
			aspectRatio	: 9 / 4,
			minContainerHeight : 320,
			minContainerWidth : 720,
		});
	}).on('hidden.bs.modal', function() {
		$("[data-triggered = true]").attr("data-triggered", false);
		$('#upload-input').val('');
		image.src = '';
		
		cropper.destroy();
		cropper = null;
	});
</script>