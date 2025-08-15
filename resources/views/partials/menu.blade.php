<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4 text-start" href="#">
            {{-- {{ trans('panel.site_title') }} --}}
            <img src="{{asset('images/harvin-chairs-logo.png')}}" style="width: 210px;" alt="">
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa-light fa-gauge-high">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>

        @can('user_management_access')
        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.userManagement.title') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('permission_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.permission.title') }}
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.role.title') }}
                        </a>
                    </li>
                @endcan
                @can('user_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.user.title') }}
                        </a>
                    </li>
                @endcan
                {{-- @can('shopping_cart_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.shopping-carts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/shopping-carts") || request()->is("admin/shopping-carts/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.shoppingCart.title') }}
                    </a>
                </li>
                @endcan --}}
            </ul>
        </li>
        @endcan

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.shops.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/shops") || request()->is("admin/shops/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-shop c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.shop.title') }}
                </a>
            </li> --}}
            @can('category_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-layer-group c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.category.title') }}
                </a>
            </li>
            @endcan
            @can('feature_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.features.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/features") || request()->is("admin/features/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-list-check c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.feature.title') }}
                </a>
            </li>
            @endcan
            @can('attribute_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.attributes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/attributes") || request()->is("admin/attributes/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-tag c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.attribute.title') }}
                </a>
            </li>
            @endcan
            @can('product_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-box c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.product.title') }}
                </a>
            </li>
            @endcan
            @can('banner_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.banners.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banners") || request()->is("admin/banners/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-images c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.banner.title') }}
                </a>
            </li>
            @endcan

            {{-- @can('advertisement_banner_access') --}}
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.advertisement-banners.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/advertisement-banners") || request()->is("admin/advertisement-banners/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.advertisementBanner.title') }}
                </a>
            </li>
            {{-- @endcan --}}
            {{-- @can('price_unit_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.price-units.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/price-units") || request()->is("admin/price-units/*") ? "c-active" : "" }}">
                    <i class="fa-regular fa-indian-rupee-sign c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.priceUnit.title') }}
                </a>
            </li>
            @endcan --}}

            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.regions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/regions") || request()->is("admin/regions/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    Shipping Regions
                </a>
            </li>
            @can('coupon_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.coupons.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/coupons") || request()->is("admin/coupons/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.coupon.title') }}
                </a>
            </li>
            @endcan
            {{-- @can('zone_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/zones*") ? "c-show" : "" }} {{ request()->is("admin/countries*") ? "c-show" : "" }} {{ request()->is("admin/carriers*") ? "c-show" : "" }} {{ request()->is("admin/zipcodes*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.shipping.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.zones.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/zones") || request()->is("admin/zones/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.zone.title') }}
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.countries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.country.title') }}
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.carriers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/carriers") || request()->is("admin/carriers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.carrier.title') }}
                            </a>
                        </li>


                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.zipcodes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/zipcodes") || request()->is("admin/zipcodes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.zipcode.title') }}
                            </a>
                        </li>

                </ul>
            </li>
            @endcan --}}
            @can('testimonial_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/testimonials*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.module.title') }}
                </a>

                <ul class="c-sidebar-nav-dropdown-items">
                        {{-- <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.menus.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/menus") || request()->is("admin/menus/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.menu.title') }}
                            </a>
                        </li> --}}
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.manage-menus") }}" class="c-sidebar-nav-link {{ request()->is("admin/menus") || request()->is("admin/menus/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.menu.title') }}
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.testimonials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/testimonials") || request()->is("admin/testimonials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.testimonial.title') }}
                            </a>
                        </li>
                        {{-- @can('print_medium_access') --}}
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.print-media.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/print-media") || request()->is("admin/print-media/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.printMedium.title') }}
                            </a>
                        </li>
                    {{-- @endcan --}}
                    {{-- @can('static_page_access') --}}
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.static-pages.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/static-pages") || request()->is("admin/static-pages/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.staticPage.title') }}
                            </a>
                        </li>
                    {{-- @endcan
                    @can('our_store_access') --}}
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.our-stores.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/our-stores") || request()->is("admin/our-stores/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ourStore.title') }}
                            </a>
                        </li>
                    {{-- @endcan --}}

                </ul>
            </li>
            @endcan

            {{-- <li class="c-sidebar-nav-dropdown {{ request()->is("admin/creative-cuts-categories*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.creativeCut.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.creative-cuts-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/creative-cuts-categories") || request()->is("admin/creative-cuts-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.creativeCutsCategory.title') }}
                            </a>
                        </li>

                </ul>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.product-features.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-features") || request()->is("admin/product-features/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.productFeature.title') }}
                </a>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.specific-prices.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/specific-prices") || request()->is("admin/specific-prices/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.specificPrice.title') }}
                </a>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.product-attributes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-attributes") || request()->is("admin/product-attributes/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.productAttribute.title') }}
                </a>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.attribute-values.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/attribute-values") || request()->is("admin/attribute-values/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.attributeValue.title') }}
                </a>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.attribute-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/attribute-products") || request()->is("admin/attribute-products/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.attributeProduct.title') }}
                </a>
            </li> --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.product-attribute-combinations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-attribute-combinations") || request()->is("admin/product-attribute-combinations/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.productAttributeCombination.title') }}
                </a>
            </li> --}}
            {{-- @can('tag_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.tags.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tags") || request()->is("admin/tags/*") ? "c-active" : "" }}">
                    <i class="fa-regular fa-tags c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.tag.title') }}
                </a>
            </li>
            @endcan --}}
            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.product-tags.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-tags") || request()->is("admin/product-tags/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.productTag.title') }}
                </a>
            </li> --}}

            @can('order_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/orders*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('Orders') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                            <i class="fa-light fa-receipt c-sidebar-nav-icon">

                            </i>
                            {{ trans('Orders') }}
                        </a>
                    </li>
                </ul>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.order-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/order-statuses") || request()->is("admin/order-statuses/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            {{ trans('Status') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-receipt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.order.title') }}
                </a>
            </li> --}}
            {{-- @can('contact_us_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/contact-uss*") ? "c-show" : "" }} {{ request()->is("admin/product-enquires*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.enquire.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.contact-uss.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/contact-uss") || request()->is("admin/contact-uss/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.contactUs.title') }}
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-enquires.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-enquires") || request()->is("admin/product-enquires/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productEnquire.title') }}
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.creative-cuts-enquires.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/creative-cuts-enquires") || request()->is("admin/creative-cuts-enquires/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.creativeCutsEnquire.title') }}
                            </a>
                        </li>

                </ul>
            </li>
            @endcan --}}

            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.order-products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/order-products") || request()->is("admin/order-products/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.orderProduct.title') }}
                </a>
            </li> --}}
            {{-- @can('news_letter_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/news-letters*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.newsLetter.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.news-letters.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/news-letters") || request()->is("admin/news-letters/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.newsLetter.title') }}
                            </a>
                        </li>
                </ul>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.customer-news-letters.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/customer-news-letters") || request()->is("admin/customer-news-letters/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.customerNewsletter.title') }}
                        </a>
                    </li>
            </ul>
            </li>
            @endcan --}}
            @can('review_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.reviews.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reviews") || request()->is("admin/reviews/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.review.title') }}
                </a>
            </li>
            @endcan
            @can('faq_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.faqs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faqs") || request()->is("admin/faqs/*") ? "c-active" : "" }}">
                    <i class="fa-light fa-question-circle c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.faq.title') }}
                </a>
            </li>
             @endcan
             @can('special_offer_access')
             <li class="c-sidebar-nav-item">
                 <a href="{{ route("admin.special-offers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/special-offers") || request()->is("admin/special-offers/*") ? "c-active" : "" }}">
                     <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                     </i>
                     {{ trans('cruds.specialOffer.title') }}
                 </a>
             </li>
         @endcan
            @can('email_template_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.email-templates.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/email-templates") || request()->is("admin/email-templates/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.emailTemplate.title') }}
                </a>
            </li>
            @endcan
            {{-- <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.order-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/order-statuses") || request()->is("admin/order-statuses/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.orderStatus.title') }}
                </a>
            </li> --}}

        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.edit-configuration") }}" class="c-sidebar-nav-link {{ request()->is("admin/configurations") || request()->is("admin/configurations/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                </i>
                {{ trans('global.Site_Configuration') }}
            </a>
        </li>
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))

                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-light fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>

        @endif

        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fa-regular fa-power-off c-sidebar-nav-icon"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
