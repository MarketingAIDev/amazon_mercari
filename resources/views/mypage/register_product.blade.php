@extends("layouts.sidebar")

@php
	$user = Auth::user();
@endphp

@section('content')
<main id="main" class="main">
	<div class="pagetitle">
		<h1>商品登録</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/">Amazon</a></li>
				<li class="breadcrumb-item active">商品登録</li>
			</ol>
		</nav>
	</div>

	<section class="content">
		<div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-2 col-md-12"></div>
				<div class="col-lg-8 col-md-12">
					<div class="card">
						<div class="card-body m-4">
							<div class="table-responsive">
								<table class="table table-hover product_item_list c_table theme-color mb-0">
									<thead>
										
									</thead>
									<tbody> 
											<td>【カテゴリー】:</td>
											<td><input type="text" id="category{{$machine->id}}" name="category{{$machine->id}}" class="form-control" placeholder="カテゴリー" value="{{$machine->category}}"/></td>
										</tr>
										<tr>
											<td>アックスキー:</td>
											<td><input type="text" id="access_key{{$machine->id}}" name="access_key{{$machine->id}}" class="form-control" placeholder="アックスキー" value="{{ $machine->access_key }}" /></td>
										</tr>
										<tr>
											<td>シークレットキー:</td>
											<td><input type="text" id="secret_key{{$machine->id}}" name="secret_key{{$machine->id}}" class="form-control" placeholder="シークレットキー" value="{{ $machine->secret_key }}" /></td>
										</tr>
										<tr>
											<td>CSVファイル:</td>
											<td>
												<input type="file" class="form-control csv_event" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csv{{$machine->id}}" name="csv{{$machine->id}}" />
											</td>
										</tr>
										<tr>
											<td>下落(%):</td>
											<td><input type="number" class="form-control" placeholder="50" id="down{{$machine->id}}" name="down{{$machine->id}}" min="0" max="100" value="30" /></td>
										</tr>
										<tr>
											<td>Web Hook:</td>
											<td><input type="text" class="form-control" id="web_hook{{$machine->id}}" name="web_hook{{$machine->id}}" value="{{$machine->web_hook}}" /></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-lg-12 mt-4" id="register-status{{$machine->id}}" style="display: block;">
								<div class="row">
									<div class="col text-center">
										<span id="progress-num{{$machine->id}}">0</span> 件/ <span id="total-num{{$machine->id}}">0</span> 件
									</div>
									<div class="col text-center">
										<span id="round{{$machine->id}}">0</span>回目
									</div>
								</div>
								<div class="row mt-4">
									<div class="progress col-12" id="count{{$machine->id}}">
										<div class="progress-bar progress-bar-animated bg-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 20px;" id="progress{{$machine->id}}">
											<span id="percent-num{{$machine->id}}">0%</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-12 mt-4" id="track-status{{$machine->id}}" style="display: none;">
								<div class="row">
									<div class="col text-center">
										<span id="progress-num1{{$machine->id}}">0</span> 件/ <span id="total-num1{{$machine->id}}">0</span> 件
									</div>
									<div class="col text-center">
										<span id="round1{{$machine->id}}">0</span>回目
									</div>
								</div>
								<div class="row mt-4">
									<div class="progress col-12" id="count1{{$machine->id}}">
										<div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 20px;" id="progress1{{$machine->id}}">
											<span id="percent-num1{{$machine->id}}">0%</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 text-center mt-4">
								<button type="button" id="register" class="btn btn-raised btn-primary waves-effect" onclick="register('{{$machine->id}}')">登 録</button>
								<button type="button" id="stop" class="btn btn-raised btn-warning waves-effect" onclick="stop('{{$machine->id}}')">停 止</button>
								<button type="button" id="restart" class="btn btn-raised btn-warning waves-effect" onclick="restart('{{$machine->id}}')">起 動</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-12"></div>
			</div>
		</div>
	</section>
</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
		var scanInterval = setInterval(scan, 5000);
		var machine = <?php echo $machine; ?>;
		$(document).ready(function () {
			if (machine.is_reg == 1) {
				$('#register-status'+ machine.id).css('display', 'block');
				$('#track-status'+ machine.id).css('display', 'none');

				$('#total-num'+ machine.id).html(machine.len);
				$('#round'+ machine.id).html(0);
				$('#progress-num'+ machine.id).html(machine.reg_num);

				$('#csv'+ machine.id).attr('disabled', true);
				$('#register'+ machine.id).attr('disabled', true);
			} else if (machine.is_reg == 0) {
				$('#register-status'+ machine.id).css('display', 'none');
				$('#track-status'+ machine.id).css('display', 'block');

				$('#total-num1'+ machine.id).html(machine.len);
				$('#round1'+ machine.id).html(machine.round);
				$('#progress-num1'+ machine.id).html(machine.trk_num);

				$('#csv'+ machine.id).attr('disabled', false);
				$('#register'+ machine.id).attr('disabled', false);
			}
		});

		function scan() {
			$.ajax({
				url: "{{ route('scan') }}",
				type: "get",
				data: {
					id: machine.id
				},
				success: function(response) {
					if (response.is_reg == 1) {
						$('#register-status'+ response.id).css('display', 'block');
						$('#track-status'+ response.id).css('display', 'none');

						$('#total-num'+ response.id).html(response.len);
						$('#progress-num'+ response.id).html(response.reg_num);
						var percent = Math.floor(response.reg_num / response.len * 100);
						$('#percent-num'+ response.id).html(percent + '%');
						$('#progress'+ response.id).attr('aria-valuenow', percent);
						$('#progress'+ response.id).css('width', percent + '%');
						$('#round'+ response.id).html(0);
					} else if (response.is_reg == 0) {
						$('#register-status'+ response.id).css('display', 'none');
						$('#track-status'+ response.id).css('display', 'block');

						$('#total-num1'+ response.id).html(response.len);
						$('#progress-num1'+ response.id).html(response.trk_num);
						var percent = Math.floor(response.trk_num / response.len * 100);
						$('#percent-num1'+ response.id).html(percent + '%');
						$('#progress1'+ response.id).attr('aria-valuenow', percent);
						$('#progress1'+ response.id).css('width', percent + '%');
						$('#round1'+ response.id).html(response.round);
					}

					if (percent == 100) {
						if (response.round == 0) {
							toastr.success('正常に登録されました。');
							location.href = "{{ route('list_product') }}";
						}
					}
				}
			})
		}

		const register = async (mId) => {
			var user = <?php echo $user; ?>;

			// if (user.is_permitted == 0) {
			// 	toastr.error('管理者からの許可をお待ちください。');
			// 	return;
			// }
			
			clearInterval(scanInterval);
			await $.ajax({
				url: "{{ route('stop') }}",
				type: "get",
				data:{
					id:JSON.stringify(mId)
				},
				success: function () {
					$('#total-num1'+ mId).html(0);
					$('#round1'+ mId).html(0);
					$('#progress-num1'+ mId).html(0);
					$('#percent-num1'+ mId).html('0%');
					$('#progress1'+ mId).attr('aria-valuenow', );
					$('#progress1'+ mId).css('width', '0%');
				}
			});

			if (csvFile === undefined) {
				toastr.error('CSVファイルを選択してください。');
				return;
			}

			let postData = {
				machine_id:mId,
				access_key: $('input[name="access_key' + mId + '"]').val(),
				secret_key: $('input[name="secret_key' + mId + '"]').val(),
				category: $('input[name="category' + mId + '"]').val(),
				down: $('input[name="down' + mId + '"]').val(),
				web_hook: $('input[name="web_hook' + mId + '"]').val(),
				file_name: csvFile.name,
				len: newCsvResult.length,
			};
			console.log(postData);
			// first save user exhibition setting
			await $.ajax({
				url:  "{{ route('save_machine') }}",
				type: 'post',
				headers: {
					"X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr("content")
				},
				data: {
					exData: JSON.stringify(postData)
				},
				success: function () {
					scanInterval = setInterval(scan, 5000);
					toastr.info('商品登録を開始します。');

					$('#register-status'+ mId).css('display', 'block');
					$('#track-status'+ mId).css('display', 'none');
			
					$('#csv'+ mId).attr('disabled', true);
					$('#register'+ mId).attr('disabled', true);
				}
			});

			// then start registering products with ASIN code
			postData = {
				user_id: '{{ Auth::user()->id }}',
				machine_id: mId,
				codes: newCsvResult
			};

			$.ajax({
				url: "https://amazon123.xsrv.jp/fmproxy/api/v1/amazon/getInfo",
				type: "post",
				data: {
					asin: JSON.stringify(postData)
				},
			});
		};

		const stop = (stopmId) => {
			clearInterval(scanInterval);
			$.ajax({
				url: "{{ route('stop') }}",
				type: "get",
				data:{
					id:JSON.stringify(stopmId)
				},
				success: function () {
					toastr.info('サーバーが停止されました。');

					$('#round1'+ stopmId).html(0);
					$('#round1'+ stopmId).html(0);
					$('#progress-num1'+ stopmId).html(0);
					$('#percent-num1'+ stopmId).html('0%');
					$('#progress1'+ stopmId).attr('aria-valuenow', );
					$('#progress1'+ stopmId).css('width', '0%');
				}
			});
		};

		const restart = (restartmId) => {
			scanInterval = setInterval(scan, 5000);
			$.ajax({
				url: "{{ route('restart') }}",
				type: "get",
				data:{
					id:JSON.stringify(restartmId)
				},
				success: function () {
					toastr.info('サーバーが起動されました。');
				}
			});
		}

		var newCsvResult, csvFile;
		// select csv file and convert its content into an array of ASIN code
		$('.csv_event').on('change', function(e) {
			result = e.target.id;
			let mId = result.match(/\d+/g)[0];
			clearInterval(scanInterval);

			csvFile = e.target.files[0];
			newCsvResult = [];

			// $('#progress-num'+ mId).html('0');
			// $('#percent-num'+ mId).html('0%');
			// $('#progress'+ mId).attr('aria-valuenow', 0);
			// $('#progress'+ mId).css('width', '0%');

			var ext = $('#csv'+ mId).val().split(".").pop().toLowerCase();
			if ($.inArray(ext, ["csv", "xlsx"]) === -1) {
				toastr.error('CSV、XLSXファイルを選択してください。');
				return false;
			}
			
			if (csvFile !== undefined) {
				reader = new FileReader();
				reader.onload = function (e) {
					$('#count'+ mId).css('visibility', 'visible');
					csvResult = e.target.result.split(/\n/);

					for (const i of csvResult) {
						let code = i.split('\r');
						code = i.split('"');

						if (code.length == 1) {
							code = i.split('\r');
							if (code[0] != '') {
								newCsvResult.push(code[0]);
							}
						} else {
							newCsvResult.push(code[1]);
						}
					}
					
					if (newCsvResult[0] == 'ASIN') { newCsvResult.shift(); }

					// $('#csv-name').html(csvFile.name);
					$('#total-num'+ mId).html(newCsvResult.length);
				}
				reader.readAsText(csvFile);
			}
		});
	</script>
@endpush
