<h4 class="m-text14 p-b-7">
	領貨剩餘時間
</h4>
@if($picking)
<div class="flex-c-m p-t-4 p-b-54">
	<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
		<span class="m-text10 p-b-1 days">
			{{ $picking_time['d'] }}
		</span>

		<span class="s-text5">
			日
		</span>
	</div>

	<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
		<span class="m-text10 p-b-1 hours">
			{{ $picking_time['H'] }}
		</span>

		<span class="s-text5">
			時
		</span>
	</div>

	<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
		<span class="m-text10 p-b-1 minutes">
			{{ $picking_time['i'] }}
		</span>

		<span class="s-text5">
			分
		</span>
	</div>

	<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
		<span class="m-text10 p-b-1 seconds">
			{{ $picking_time['s'] }}
		</span>

		<span class="s-text5">
			秒
		</span>
	</div>
</div>
@else
<div class="p-b-54 p-t-4 s-text13">
	目前並非領貨時間
</div>
@endif
