<table {!! $attributes !!}>
    <thead>
    <tr>
        @foreach($headers as $header)
            <th style="min-width:100px;">{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
    <tr>
        @foreach($row as $item)
        <td>{!! $item !!}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>