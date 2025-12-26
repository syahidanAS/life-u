<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            @php
                $menus = \App\Helpers\Main::getMenus();
            @endphp
            @foreach($menus as $parent)
                @can('Show ' . $parent->name)
                    <li>
                        @can('Show ' . $parent->name)
                            <a class="{{ (count($parent->children) > 0) ? 'has-arrow' : '' }}"
                                href="{{ (count($parent->children) > 0) ? '#' : $parent->url }}">
                                {!! $parent->icon !!}
                                <span class="nav-text">{{ $parent->name }}</span>
                            </a>

                            @if(count($parent->children) > 0)
                                <ul>
                                    @foreach($parent->children as $child)
                                        @can('Show ' . $child->name)
                                            <li>
                                                <a class="{{ (count($child->children) > 0) ? 'has-arrow' : '' }}"
                                                    href="{{ (count($child->children) > 0) ? '#' : $child->url }}">
                                                    {!! $child->icon ?? '' !!}
                                                    {{ $child->name }}
                                                </a>

                                                @if(count($child->children) > 0)
                                                    <ul>
                                                        @foreach($child->children as $children)
                                                            @can('Show ' . $children->name)
                                                                <li><a href="{{ $children->url }}">{{ $children->name }}</a></li>
                                                            @endcan
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            @endif
                        @endcan
                    </li>

                @endcan
            @endforeach
        </ul>

    </div>
</div>