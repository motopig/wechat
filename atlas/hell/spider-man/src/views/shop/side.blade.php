<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
    <ul class="list-group">
        <li class="list-group-item no-padder @if(isset($shop_menu)) active @endif">
          <a href="{{URL::to('angel/shop/index')}}" style="display:block;padding:10px 15px;">
            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            商店首页
          </a>
        </li>
        <li class="list-group-item no-padder @if(isset($plan_menu)) active @endif">
          <a href="{{URL::to('angel/shop/plan')}}" style="display:block;padding:10px 15px;">
            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            云号套餐
          </a>
        </li>
        <li class="list-group-item no-padder @if(isset($iot_menu)) active @endif">
          <a href="{{URL::to('angel/shop/iot')}}" style="display:block;padding:10px 15px;">
            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            智能硬件
          </a>
        </li>
        <li class="list-group-item no-padder @if(isset($app_menu)) active @endif">
          <a href="{{ URL::to('angel/appCenter') }}" style="display:block;padding:10px 15px;">
            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            去应用中心
          </a>
        </li>
        <li class="list-group-item no-padder @if(isset($promotion_menu)) active @endif">
          <a href="{{URL::to('angel/shop/promotion')}}" style="display:block;padding:10px 15px;">
            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            优惠促销
          </a>
        </li>
      </ul>
</div>