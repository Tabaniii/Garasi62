@php
    $footerContactTitle = \App\Models\SiteSetting::get('footer_contact_title', 'Hubungi Kami Sekarang!');
    $footerPhone = \App\Models\SiteSetting::get('footer_phone', '(+12) 345 678 910');
    $siteEmail = \App\Models\SiteSetting::get('site_email', \App\Models\SiteSetting::get('footer_email', 'Colorlib@gmail.com'));
    $siteName = \App\Models\SiteSetting::get('site_name', 'Ride62');
    $siteLogo = \App\Models\SiteSetting::get('site_logo', 'ride62/img/ride62-fix.svg');
    $logoFallback = asset('ride62/img/ride62-fix.svg');
    $footerAboutText = \App\Models\SiteSetting::get('footer_about_text', 'Any questions? Let us know in store at 625 Gloria Union, California, United Stated or call us on (+1) 96 123 8888');
    $socialLinksJson = \App\Models\SiteSetting::get('footer_social_links', '');
    $socialLinks = [];
    if (!empty($socialLinksJson)) {
        $socialLinks = json_decode($socialLinksJson, true) ?? [];
    } else {
        $legacyPlatforms = [
            'facebook' => 'footer_facebook_url',
            'twitter' => 'footer_twitter_url',
            'google' => 'footer_google_url',
            'instagram' => 'footer_instagram_url',
            'skype' => 'footer_skype_url'
        ];
        foreach ($legacyPlatforms as $icon => $key) {
            $url = \App\Models\SiteSetting::get($key, '');
            if ($url) {
                $socialLinks[] = ['platform' => ucfirst($icon), 'url' => $url, 'icon' => $icon];
            }
        }
    }
    $footerInfo1Title = \App\Models\SiteSetting::get('footer_info1_title', 'Informasi');
    $footerInfo1List = \App\Models\SiteSetting::get('footer_info1_list', "Pembelian|\nPembayaran|\nPengiriman|\nPengembalian|");
    $footerInfo2Title = \App\Models\SiteSetting::get('footer_info2_title', 'Informasi');
    $footerInfo2List = \App\Models\SiteSetting::get('footer_info2_list', "Hatchback|\nSedan|\nSUV|\nCrossover|");
    $footerBrandsTitle = \App\Models\SiteSetting::get('footer_brands_title', 'Merek Teratas');
    $footerBrandsList = \App\Models\SiteSetting::get('footer_brands_list', "Abarth|\nAcura|\nAlfa Romeo|\nAudi|\nBMW|\nChevrolet|\nFerrari|\nHonda|");
@endphp

@php
    $footerInfo1Items = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $footerInfo1List)));
    $footerInfo2Items = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $footerInfo2List)));
    $footerBrandItems = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $footerBrandsList)));
    $footerBrandItems = array_slice($footerBrandItems, 0, 4);
@endphp

<footer class="footer set-bg" data-setbg="{{ asset('img/footer-bg.jpg') }}">
    <div class="container">
        <div class="footer__contact">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="footer__contact__title">
                        <h2>{{ $footerContactTitle }}</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="footer__contact__option">
                        @if($footerPhone)
                            <div class="option__item"><i class="fa fa-phone"></i> {{ $footerPhone }}</div>
                        @endif
                        @if($siteEmail)
                            <div class="option__item email"><i class="fa fa-envelope-o"></i> {{ $siteEmail }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="/"><img src="{{ media_url($siteLogo) }}" alt="{{ $siteName }}"
                                onerror="this.onerror=null; this.src='{{ $logoFallback }}';"></a>
                    </div>
                    <p>{{ $footerAboutText }}</p>
                    <div class="footer__social">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social['url'] }}" class="{{ strtolower($social['platform'] ?? 'link') }}"
                                target="_blank" rel="noopener" title="{{ $social['platform'] ?? '' }}">
                                <i class="fab fa-{{ $social['icon'] ?? 'link' }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1 col-md-3">
                <div class="footer__widget">
                    <h5>{{ $footerInfo1Title }}</h5>
                    <ul>
                        @foreach($footerInfo1Items as $item)
                            @php
                                $parts = explode('|', $item);
                                $label = $parts[0] ?? $item;
                                $url = !empty($parts[1]) ? $parts[1] : '#';
                            @endphp
                            <li><a href="{{ $url }}"><i class="fa fa-angle-right"></i> {{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <div class="footer__widget">
                    <h5>{{ $footerInfo2Title }}</h5>
                    <ul>
                        @foreach($footerInfo2Items as $item)
                            @php
                                $parts = explode('|', $item);
                                $label = $parts[0] ?? $item;
                                $url = !empty($parts[1]) ? $parts[1] : '#';
                            @endphp
                            <li><a href="{{ $url }}"><i class="fa fa-angle-right"></i> {{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer__brand">
                    <h5>{{ $footerBrandsTitle }}</h5>
                    <ul>
                        @foreach($footerBrandItems as $item)
                            <li><a href="{{ route('cars', ['brand' => $item]) }}"><i class="fa fa-angle-right"></i>
                                    {{ $item }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        <div class="footer__copyright__text">
            <p>Copyright &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script> Seluruh hak cipta dilindungi | Templet ini dibuat dengan <i class="fa fa-heart"
                    aria-hidden="true"></i> oleh <a href="https://colorlib.com" target="_blank">Colorlib</a>
            </p>
        </div>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
    </div>
</footer>