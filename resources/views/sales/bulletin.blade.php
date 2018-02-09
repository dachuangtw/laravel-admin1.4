@extends('sales.layouts.master')

@section('content')
<section class="bgwhite p-t-60">
	<div class="container">
		<div class="bulletin-height col-lg p-b-75">
			<div class="p-r-50 p-r-0-lg min-h-550">

				@forelse ($bulletins as $bulletin)
					<div class="item-blog p-b-80">
						<div class="item-blog-txt p-t-33">
							<h4 class="m-text24 p-b-11">
								{{ $bulletin->note_title }}
							</h4>

							<div class="s-text8 flex-w flex-m p-b-21">
								<span>
									最後更新: {{ $bulletin->hasOneWriter->name }}
									<span class="m-l-3 m-r-6">|</span>
								</span>

								<span>
									公告時間: {{ $bulletin->note_at }}
								</span>
							</div>

							<p class="p-b-12">
								{!! $bulletin->note_content !!}
							</p>

						</div>
					</div>
				@empty
					<div class="item-blog-txt p-t-33">
						<p class="p-b-12 t-center">
							無公告
						</p>

					</div>
				@endforelse

			</div>

			@include('sales.parts.pagination', ['paginator' => $bulletins])

		</div>
	</div>
</section>
@endsection
