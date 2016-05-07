<aside class="bg-white dk aside-sm hidden-print" id="nav">
	<section class="vbox">
		<section class="w-f-md scrollable">
			<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
				<nav class="nav-primary hidden-xs">
                    
                    @if(!empty($side_menu))
                    
                    <?php
                        $route_path?'':$route_path='yunke';
                        $current_route_path = $route_path;
                        if(isset($menu_route_path)){
                            $current_route_path = $menu_route_path;
                        }
                    ?>
                    
                    <ul class="nav bg clearfix">
                        @if(array_key_exists('group',$side_menu) && !empty($side_menu['group']))
                        @foreach($side_menu['group'] as $group)
                            <li class="active" id="{{{$group['id']}}}">
                                <a class="active">
                                    @if($group['icon'])
                                    <i class="{{$group['icon']}}"></i>
                                    @endif
                                    <span>{{{$group['title']}}}</span>
                                </a>
                                @if(array_key_exists('menu',$side_menu) && array(array_key_exists($group['id'],$side_menu['menu'])))
                                <ul class="nav dk text-sm">
                                @foreach($side_menu['menu'][$group['id']] as $menu)
                                
                                    <li @if($menu['url']==$current_route_path) class="active" @endif>
                                        <a @if(array_key_exists('target',$menu)) target="{{{$menu['target']}}}" @endif href="{{URL::to($menu['url'])}}" @if($menu['url']==$current_route_path) class="active" @endif >
                                            @if($menu['icon'])<i class="{{{$menu['icon']}}}"></i>@endif
                                            <span>{{{$menu['title']}}}</span>
                                        </a>
                                    </li>
                                
                                @endforeach
                                </ul>
                                @endif
                            </li>
                        @endforeach
                        @endif
                    </ul>
                    
                    @endif
                    
				</nav>
				<div class="hellCsrfToken" csrfToken="{{Session::token()}}"></div>
                <div class="hellCsrfGuid" CsrfGuid="{{Session::get('guid')}}" /></div>
			</div>
		</section>
		
	</section>
</aside>

