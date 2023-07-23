@extends("layouts.sidebar")

@php
$users = App\Models\User::all();
@endphp

@section('content')
<div class="page-heading" id="product_list">
	<div class="pagetitle">
		<h3>管理者ページ</h3>
	</div>

	<section class="section">
		<div class="body_scroll">
			<div class="container-fluid">
				<div class="row clearfix">
					<div class="col-12">
						<div class="card card-info card-outline">
							<div class="table-responsive">
								<table class="table table-hover product_item_list c_table theme-color mb-0">
									<thead>
										<tr>
											<th>削除</th>
											<th>名前</th>
											<th>メール</th>
											<th>許可</th>
										</tr>
									</thead>
									<tbody>
										@if (count($users) == 0)
										<tr>
											<td colspan="4" style="text-align: center;">データがありません。</td>
										</tr>
										@endif
										@foreach($users as $u)
										@if ($u->role == 'admin') @continue @endif
										<tr data-id={{$u->id}}>
											<td>
												<button class="btn btn-icon btn-danger" type="button" onclick="deleteAccount(event);"><i class="bi bi-trash"></i></button>
											</td>
											<td rowspan="1" colspan="1">{{ $u->family_name }}</td>
											<td rowspan="1" colspan="1">{{ $u->email }}</td>
											<td rowspan="1" colspan="1">
												<div class="form-check form-switch" style="margin-top: 10px;">
													<input type="checkbox" class="form-check-input" id={{"customSwitch".$u->id}} @if ($u->is_permitted == 1) checked @endif onchange="permitAccount(event);">
													<label class="custom-control-label" for={{"customSwitch".$u->id}}></label>
												</div>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection

@push("scripts")
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>:
<script>
	const deleteAccount = (event) => {
		if (!window.confirm('アカウントを削除すると、関連するすべてのデータが削除されます。\n操作を続行してもよろしいですか？')) {
			return;
		}
		let _tr = $(event.target).parents('tr');
		let userId = _tr.data('id');

		$.ajax({
			url: '{{ route("delete_account") }}',
			type: 'get',
			data: {
				id: userId
			},
			success: function() {
				Toastify({
					text: "アカウントは正常に削除されました。",
					duration: 2500,
					close: true,
					gravity: "top",
					position: "right",
					backgroundColor: "#4fbe87",
				}).showToast();
				// toastr.success("アカウントは正常に削除されました。");
				_tr.remove();
			}
		});
	};

	const permitAccount = (event) => {
		let isPermitted = (event.target.checked == true) ? 1 : 0;
		$.ajax({
			url: '{{ route("permit_account") }}',
			type: 'get',
			data: {
				id: event.target.id.replace('customSwitch', ''),
				isPermitted: isPermitted
			},
			success: function(res) {
				if (res == 1) {
					Toastify({
						text: "許可されました。",
						duration: 2500,
						close: true,
						gravity: "top",
						position: "right",
						backgroundColor: "#4fbe87",
					}).showToast();
					// toastr.success("許可されました。");
				} else if (res == 0) {
					Toastify({
						text: "許可がキャンセルされました。",
						duration: 2500,
						close: true,
						gravity: "top",
						position: "right",
						backgroundColor: "#4fbe87",
					}).showToast();
					// toastr.success("許可がキャンセルされました。");
				}
			}
		});
	};
</script>
@endpush