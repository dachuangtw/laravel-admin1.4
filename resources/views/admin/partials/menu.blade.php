@if(Admin::user()->visible($item['roles']))
    @if(!isset($item['children']))
        <li>
            <a href="{{ Admin::url($item['uri']) }}"><i style="font-size:medium;" class="fa {{$item['icon']}}"></i>
                <span  style="font-size:medium;">{{$item['title']}}</span>
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="#">
                <i style="font-size:medium;" class="fa {{$item['icon']}}"></i>
                <span style="font-size:medium;">{{$item['title']}}</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                @foreach($item['children'] as $item)
                    @include('admin::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif