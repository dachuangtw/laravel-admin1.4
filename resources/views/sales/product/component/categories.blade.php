<li class="p-t-4">
	@if($active)
	<span class="s-text13 active1">
		<span class="ti-angle-right"></span>
		{{ $slot }}
	</span>

	@else

	<a href="{{ $url or '#' }}" class="s-text13">
		{{ $slot }}
	</a>

	@endif
</li>
