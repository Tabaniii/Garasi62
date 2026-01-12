@extends('template.temp')

<!-- @section('title', 'Home - GARASI62') Set the title for the page -->

@section('content')
<!-- Hero Section Begin -->
<section class="hero spad set-bg" data-setbg="img/hero-bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="hero__text">
                    <div class="hero__text__title">
                        <span>TEMUKAN MOBIL IMPIANMU DISINI</span>
                        <h2>Porsche Cayenne S</h2>
                    </div>
                    <div class="hero__text__price">
                        <div class="car-model">Model 2019</div>
                        <h2>IDR 2.2M</h2>
                    </div>
                    <a href="#" class="primary-btn"><img src="img/wheel.png" alt=""> Test Drive</a>
                    <a href="{{ route('about') }}" class="primary-btn more-btn">Learn More</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Rental Mobil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Beli Mobil</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="hero__tab__form">
                                <form action="{{ route('cars') }}" method="GET" id="rentForm">
                                    <input type="hidden" name="tipe" value="rent">
                                    <div class="select-list">
                                        <div class="select-list-item">
                                            <p>Pilih Tahun</p>
                                            <select name="tahun">
                                                <option value="" data-display="Pilih Tahun">Pilih Tahun</option>
                                                @foreach($tahunList as $tahun)
                                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Merk Mobil</p>
                                            <select name="brand">
                                                <option value="" data-display="Pilih Brand">Pilih Brand</option>
                                                @foreach($brands as $brand)
                                                <option value="{{ $brand }}">{{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Transmisi</p>
                                            <select name="transmisi">
                                                <option value="" data-display="Pilih Transmisi">Pilih Transmisi</option>
                                                @foreach($transmisiList as $transmisi)
                                                <option value="{{ $transmisi }}">{{ $transmisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Kapasitas Mesin</p>
                                            <select name="kapasitasmesin">
                                                <option value="" data-display="Pilih Kapasitas Mesin">Pilih Kapasitas Mesin</option>
                                                @foreach($kapasitasmesinList as $kapasitasmesin)
                                                <option value="{{ $kapasitasmesin }}">{{ $kapasitasmesin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="filter-price">
                                        <p>Harga:</p>
                                        <div class="price-input-row" style="margin-bottom: 25px;">
                                            <div class="price-input-item">
                                                <label>Harga Min:</label>
                                                <input type="number" name="min_price" id="rentMinPrice" placeholder="Min" value="{{ $minPrice ?? 0 }}" min="0">
                                            </div>
                                            <div class="price-input-item">
                                                <label>Harga Max:</label>
                                                <input type="number" name="max_price" id="rentMaxPrice" placeholder="Max" value="{{ $maxPrice ?? 1000000000 }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="site-btn">Searching</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="hero__tab__form">
                                <form action="{{ route('cars') }}" method="GET" id="buyForm">
                                    <input type="hidden" name="tipe" value="buy">
                                    <div class="select-list">
                                        <div class="select-list-item">
                                            <p>Pilih Tahun</p>
                                            <select name="tahun">
                                                <option value="" data-display="Pilih Tahun">Pilih Tahun</option>
                                                @foreach($tahunList as $tahun)
                                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Merk Mobil</p>
                                            <select name="brand">
                                                <option value="" data-display="Pilih Brand">Pilih Brand</option>
                                                @foreach($brands as $brand)
                                                <option value="{{ $brand }}">{{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Transmisi</p>
                                            <select name="transmisi">
                                                <option value="" data-display="Pilih Transmisi">Pilih Transmisi</option>
                                                @foreach($transmisiList as $transmisi)
                                                <option value="{{ $transmisi }}">{{ $transmisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Kapasitas Mesin</p>
                                            <select name="kapasitasmesin">
                                                <option value="" data-display="Pilih Kapasitas Mesin">Pilih Kapasitas Mesin</option>
                                                @foreach($kapasitasmesinList as $kapasitasmesin)
                                                <option value="{{ $kapasitasmesin }}">{{ $kapasitasmesin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="filter-price" style="margin-bottom: 25px;">
                                        <p>Harga:</p>
                                        <div class="price-input-row">
                                            <div class="price-input-item">
                                                <label>Harga Min:</label>
                                                <input type="number" name="min_price" id="buyMinPrice" placeholder="Min" value="{{ $minPrice ?? 0 }}" min="0">
                                            </div>
                                            <div class="price-input-item">
                                                <label>Harga Max:</label>
                                                <input type="number" name="max_price" id="buyMaxPrice" placeholder="Max" value="{{ $maxPrice ?? 1000000000 }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="site-btn">Searching</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Services</span>
                    <h2>What We Offers</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-1.png" alt="">
                    <h5>Rental A Cars</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-2.png" alt="">
                    <h5>Buying A Cars</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-3.png" alt="">
                    <h5>Car Maintenance</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-4.png" alt="">
                    <h5>Support 24/7</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Feature Section Begin -->
<section class="feature spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="feature__text">
                    <div class="section-title">
                        <span>Our Feature</span>
                        <h2>We Are a Trusted Name In Auto</h2>
                    </div>
                    <div class="feature__text__desc">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                    </div>
                    <div class="feature__text__btn">
                        <a href="{{ route('about') }}" class="primary-btn">About Us</a>
                        <a href="#" class="primary-btn partner-btn">Our Partners</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-4">
                <div class="row">
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-1.png" alt="">
                            </div>
                            <h6>Engine</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-2.png" alt="">
                            </div>
                            <h6>Turbo</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-3.png" alt="">
                            </div>
                            <h6>Colling</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-4.png" alt="">
                            </div>
                            <h6>Suspension</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-5.png" alt="">
                            </div>
                            <h6>Electrical</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-6.png" alt="">
                            </div>
                            <h6>Brakes</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Feature Section End -->

<!-- Car Section Begin -->
<section class="car spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Car</span>
                    <h2>Best Vehicle Offers</h2>
                </div>
                <ul class="filter__controls">
                    <li class="active" data-filter="*">Most Researched</li>
                    <li data-filter=".sale">Latest on sale</li>
                </ul>
            </div>
        </div>
        <div class="row car-filter">
            <div class="col-lg-3 col-md-4 col-sm-6 mix">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-1.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-6.jpg" alt="">
                        <img src="img/cars/car-3.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2016</div>
                            <h5><a href="#">Porsche cayenne turbo s</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option">For Rent</span>
                            <h6>$218<span>/Month</span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix sale">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-2.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-6.jpg" alt="">
                        <img src="img/cars/car-4.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2020</div>
                            <h5><a href="#">Toyota camry asv50l-jeteku</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option sale">For Sale</span>
                            <h6>$73,900</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-3.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-6.jpg" alt="">
                        <img src="img/cars/car-5.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2017</div>
                            <h5><a href="#">Bmw s1000rr 2019 m</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option">For Rent</span>
                            <h6>$299<span>/Month</span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix sale">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-4.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-2.jpg" alt="">
                        <img src="img/cars/car-1.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2019</div>
                            <h5><a href="#">Lamborghini huracan evo</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option sale">For Sale</span>
                            <h6>$120,000</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-5.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-7.jpg" alt="">
                        <img src="img/cars/car-2.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2018</div>
                            <h5><a href="#">Audi q8 2020</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option">For Rent</span>
                            <h6>$319<span>/Month</span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix sale">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-6.jpg" alt="">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-3.jpg" alt="">
                        <img src="img/cars/car-1.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2016</div>
                            <h5><a href="#">Mustang shelby gt500</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option sale">For Sale</span>
                            <h6>$730,900</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-7.jpg" alt="">
                        <img src="img/cars/car-2.jpg" alt="">
                        <img src="img/cars/car-4.jpg" alt="">
                        <img src="img/cars/car-1.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2020</div>
                            <h5><a href="#">Lamborghini aventador A90</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option">For Rent</span>
                            <h6>$422<span>/Month</span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mix">
                <div class="car__item">
                    <div class="car__item__pic__slider owl-carousel">
                        <img src="img/cars/car-8.jpg" alt="">
                        <img src="img/cars/car-3.jpg" alt="">
                        <img src="img/cars/car-5.jpg" alt="">
                        <img src="img/cars/car-2.jpg" alt="">
                    </div>
                    <div class="car__item__text">
                        <div class="car__item__text__inner">
                            <div class="label-date">2017</div>
                            <h5><a href="#">Porsche cayenne turbo s</a></h5>
                            <ul>
                                <li><span>35,000</span> mi</li>
                                <li>Auto</li>
                                <li><span>700</span> hp</li>
                            </ul>
                        </div>
                        <div class="car__item__price">
                            <span class="car-option">For Rent</span>
                            <h6>$322<span>/Month</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Car Section End -->

<!-- Chooseus Section Begin -->
<section class="chooseus spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="chooseus__text">
                    <div class="section-title">
                        <h2>Why People Choose Us</h2>
                        <p>Duis aute irure dolorin reprehenderits volupta velit dolore fugiat nulla pariatur excepteur sint occaecat cupidatat.</p>
                    </div>
                    <ul>
                        <li><i class="fa fa-check-circle"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li><i class="fa fa-check-circle"></i> Integer et nisl et massa tempor ornare vel id orci.</li>
                        <li><i class="fa fa-check-circle"></i> Nunc consectetur ligula vitae nisl placerat tempus.</li>
                        <li><i class="fa fa-check-circle"></i> Curabitur quis ante vitae lacus varius pretium.</li>
                    </ul>
                    <a href="{{ route('about') }}" class="primary-btn">About Us</a>
                </div>
            </div>
        </div>
    </div>
    <div class="chooseus__video set-bg">
        <img src="img/chooseus-video.png" alt="">
        <a href="https://www.youtube.com/watch?v=Xd0Ok-MkqoE" class="play-btn video-popup"><i class="fa fa-play"></i></a>
    </div>
</section>
<!-- Chooseus Section End -->

<!-- Latest Blog Section Begin -->
<section class="latest spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Blog</span>
                    <h2>Latest News Updates</h2>
                    <p>Sign up for the latest Automobile Industry information and more. Duis aute<br /> irure dolorin reprehenderits volupta velit dolore fugiat.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="latest__blog__item">
                    <div class="latest__blog__item__pic set-bg" data-setbg="img/latest-blog/lb-1.jpg">
                        <ul>
                            <li>By Polly Williams</li>
                            <li>Dec 19, 2018</li>
                            <li>Comment</li>
                        </ul>
                    </div>
                    <div class="latest__blog__item__text">
                        <h5>Benjamin Franklin S Method Of Habit Formation</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                        <a href="#">View More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="latest__blog__item">
                    <div class="latest__blog__item__pic set-bg" data-setbg="img/latest-blog/lb-2.jpg">
                        <ul>
                            <li>By Mattie Ramirez</li>
                            <li>Dec 19, 2018</li>
                            <li>Comment</li>
                        </ul>
                    </div>
                    <div class="latest__blog__item__text">
                        <h5>How To Set Intentions That Energize You</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                        <a href="#">View More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="latest__blog__item">
                    <div class="latest__blog__item__pic set-bg" data-setbg="img/latest-blog/lb-3.jpg">
                        <ul>
                            <li>By Nicholas Brewer</li>
                            <li>Dec 19, 2018</li>
                            <li>Comment</li>
                        </ul>
                    </div>
                    <div class="latest__blog__item__text">
                        <h5>Burning Desire Golden Key Or Red Herring</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                        <a href="#">View More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Blog Section End -->

<!-- Cta Begin -->
<div class="cta">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="cta__item set-bg" data-setbg="img/cta/cta-1.jpg">
                    <h4>Do You Want To Buy A Car</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="cta__item set-bg" data-setbg="img/cta/cta-2.jpg">
                    <h4>Do You Want To Rent A Car</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cta End -->

<style>
    .price-range-preview {
        margin-bottom: 15px;
        padding: 8px 0;
    }

    .price-range-preview small {
        display: block;
        color: #666;
        font-size: 12px;
        font-style: italic;
    }

    .price-input-row {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .price-input-item {
        flex: 1;
    }

    .price-input-item label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 500;
        color: #19191a;
    }

    .price-input-item input[type="number"] {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
        font-size: 14px;
        color: #19191a;
        transition: border-color 0.3s ease;
    }

    .price-input-item input[type="number"]:focus {
        outline: none;
        border-color: #df2d24;
    }

    .price-input-item input[type="number"]::placeholder {
        color: #999;
    }

    @media (max-width: 575px) {
        .price-input-row {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<script>
    (function() {
        function initNiceSelect() {
            // Inisialisasi ulang nice-select untuk memastikan placeholder muncul
            if (typeof $ !== 'undefined' && typeof $.fn.niceSelect !== 'undefined') {
                $('#rentForm select, #buyForm select').niceSelect('update');
            } else {
                // Jika belum ter-load, coba lagi setelah 100ms (max 10 kali)
                if (typeof initNiceSelect.attemptCount === 'undefined') {
                    initNiceSelect.attemptCount = 0;
                }
                initNiceSelect.attemptCount++;
                if (initNiceSelect.attemptCount < 10) {
                    setTimeout(initNiceSelect, 100);
                }
            }
        }

        // Tunggu hingga semua script di template ter-load
        if (window.addEventListener) {
            window.addEventListener('load', function() {
                setTimeout(initNiceSelect, 300);
            });
        } else if (window.attachEvent) {
            window.attachEvent('onload', function() {
                setTimeout(initNiceSelect, 300);
            });
        } else {
            // Fallback jika event listener tidak tersedia
            setTimeout(initNiceSelect, 1000);
        }
    })();
</script>
@endsection