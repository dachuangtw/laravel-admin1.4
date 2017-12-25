<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-default btn-sm {{ \Request::get('resign', 'all') == $option ? 'active' : '' }}">
        <input type="radio" class="sales-resign" value="{{ $option }}">{{$label}}
    </label>
    @endforeach
</div>