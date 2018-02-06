<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-default btn-sm {{ \Request::get('assign_date', 'thismonth') == $option ? 'active' : '' }}">
        <input type="radio" class="date-chooser" value="{{ $option }}">{{$label}}
    </label>
    @endforeach
</div>