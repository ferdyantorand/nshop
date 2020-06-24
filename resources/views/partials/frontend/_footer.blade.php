
<footer id="footer" class="footer footer-1">
    <!-- Widget Section
    ============================================= -->
    <div class="footer-widget">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 footer--widget footer--widget-about centered">
                    <div class="footer--widget-title" style="padding-bottom:2%;">
                        <img  src="{{ asset('images/icons/logo.jpg') }}" alt="Nama Logo" style="width: 25%;">
                    </div>
                    <div class="footer--widget-content">

                        <div class="col-xs-12 col-sm-6 col-md-6 footer--widget footer--widget-about">
                            <ul class="list-unstyled mb-0">
                                <li><a href="{{route('others', ['type'=>'Term-Condition'])}}">T&C'S</a></li>
                                <li><a href="{{route('others', ['type'=>'FAQ'])}}">FAQ'S</a></li>
                                {{--<li style="color:black;">SHIPPING</li>--}}
                                <li><a href="{{route('others', ['type'=>'Privacy-Policy'])}}">PRIVACY</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 footer--widget footer--widget-about">
                            <ul class="list-unstyled mb-0">
                                <li><a href="{{route('about.us')}}">ABOUT</a></li>
                                <li><a href="{{route('contact-form')}}">CONTACT</a></li>
                                <li>
                                    {{--<img src="{{ asset('images/icons/nama-brand-pinterest.svg') }}" class="width-20">--}}
                                    <a href="http://instagram.com/namaofficial/" target="_blank"><img src="{{ asset('images/icons/nama-brand-instagram.svg') }}" class="width-20"></a>
                                    <a href="https://www.facebook.com/worldofnama" target="_blank"><img src="{{ asset('images/icons/nama-brand-facebook.svg') }}" class="width-20"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .col-md-3 end -->
                <div class="col-xs-12 col-sm-6 col-md-6 centered">
                    <div style="margin-bottom: 4%;">
                        <span class="family-abril" style="font-size: 22px;color:black;">Join Our Newsletter</span>
                    </div>
                    <div>
                        <form id="subscription_form">
                            <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}">

                            <input type="text" class="form-control" name="subscribe_name" id="subscribe_name" placeholder="NAME" required/>
                            <input type="email" class="form-control" name="subscribe_email" id="subscribe_email" placeholder="EMAIL" required/>
                            <button type="submit" class="newsletter-button"><i class="fa fa-long-arrow-right"></i></button>
                        </form>
                        <div id="subscribe_success_message" class="row mb-3" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-center">Thank you for registering</h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <p>
                            COPYRIGHT 2020 NAMA-OFFICIAL <br>
                            SITE DESIGNED BY <span style="font-weight: bold;">MADE SOMEWHERE</span>
                        </p>
                    </div>
                </div><!-- .col-md-3 end -->
            </div>
        </div><!-- .container end -->
    </div><!-- .footer-widget end -->

</footer>

