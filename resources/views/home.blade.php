@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{--
    Homepage narrative arc: minimal -> intriguing -> informative ->
    impressive -> convincing -> action.

      1. Hero .................. minimal      (unchanged)
      2. Statement ............. intriguing   -> why this practice exists
      3. Services .............. informative  -> what it offers      -> /services
      4. Featured frames ....... impressive   -> what you can buy    -> /products
      5. Sale banner ........... impressive   (existing partial)
      6. Promise ............... convincing   -> what's different    -> /promise
      7. Team + testimonial .... convincing   -> who's behind it     -> /about
      8. Booking ............... action       -> what to do next     -> /contact

    Every module is composed for this page rather than lifted from the
    standalone one it links to: Services is an asymmetric heading/list
    split (the Services page is a numbered full-width list), Promise is a
    single gradient band (the Promise page is alternating split panels),
    and the team strip carries names and roles only, no bios. The subsets
    themselves are chosen in HomeController, which documents each pick.

    Backgrounds deliberately alternate — dark hero, white, tinted, white,
    dark banner, gradient, white, tinted — so the page has pacing instead
    of reading as one long scroll of sections.
--}}

@include('partials.hero')


{{-- 2. Statement — intriguing -------------------------------------------- --}}
<section class="container py-5 my-md-4">
    <div class="row justify-content-center text-center">
        <div class="col-lg-9 col-xl-8">
            <p class="section-eyebrow text-brown mb-4">Northlight Optical &middot; Portland, Oregon</p>

            {{--
                The blur-to-focus reveal earns its keep on exactly this
                line: it is the page's thesis, and sharpening into focus is
                what the business does. Used once here, not on every
                heading down the page.
            --}}
            <h2 class="display-5 mb-4 reveal reveal-blur">
                Good eye care shouldn&rsquo;t require you to explain yourself first.
            </h2>

            <p class="fs-5 text-muted mb-0">
                We built this practice around the parts of an eye exam that usually get rushed:
                enough time to ask questions, equipment that adjusts to you instead of the other
                way round, and someone on the team who can talk it through in the language you
                think in.
            </p>
        </div>
    </div>
</section>


{{-- 3. Services — informative -------------------------------------------- --}}
<section class="bg-light py-5">
    <div class="container py-md-4">
        {{-- gy-5 g-lg-5, not g-5: a 3rem horizontal gutter gives the row
             -24px side margins while .container only pads 12px, so below
             sm (where the container is fluid rather than centred with
             slack) a bare g-5 pushes the page 12px past the viewport. --}}
        <div class="row gy-5 g-lg-5 align-items-start">

            {{-- Heading column: carries the section's framing and its forward link. --}}
            <div class="col-lg-5">
                <p class="section-eyebrow text-brown mb-3">What we do</p>
                <h3 class="mb-3">Everyday eye care, done properly.</h3>
                <p class="text-muted mb-4">
                    Exams, lenses, and fittings are the core of the practice. Kids&rsquo; eyewear,
                    low-vision consultations, and free walk-in frame adjustments sit alongside them.
                </p>
                <a href="{{ route('services') }}" class="btn btn-outline-primary px-3">All six services</a>
            </div>

            {{-- List column: icon-led rows, tighter than the Services page's numbered list. --}}
            <div class="col-lg-7">
                @foreach($services as $service)

                <div class="d-flex gap-3 gap-md-4 {{ $loop->last ? '' : 'mb-4 pb-4 border-bottom' }} reveal">
                    <div class="text-brown flex-shrink-0 pt-1">
                        @include('partials.icon', ['icon' => $service['icon'], 'size' => 30])
                    </div>
                    <div>
                        <h5 class="mb-2">{{ $service['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $service['description'] }}</p>
                    </div>
                </div>

                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- 4. Featured frames — impressive --------------------------------------
     Uses the shared product card rather than a homepage-only variant: the
     card carries the add-to-cart form, sale badge, and lens-zoom hover,
     and a shopping module should look like one. The homepage-specific part
     is the framing around it — a real heading and a proper button, in
     place of the muted "See More" text link this section used to have.
------------------------------------------------------------------------ --}}
<section class="container py-5 my-md-4">

    <div class="row align-items-end mb-5">
        <div class="col-md-8">
            <p class="section-eyebrow text-brown mb-3">The collection</p>
            <h3 class="mb-0">Frames worth a second look.</h3>
        </div>
        <div class="col-md-4 d-md-flex justify-content-md-end mt-3 mt-md-0">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary px-3">Browse all frames</a>
        </div>
    </div>

    @if($featuredProducts->count() > 0)

    <div class="row">

        @foreach($featuredProducts as $product)

        <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.card')
        </div>

        @endforeach

    </div>

    @else

    <p class="text-muted">No featured frames right now — <a href="{{ route('products.index') }}">browse the full range</a>.</p>

    @endif

</section>


@include('partials.sale')


{{-- 6. Promise — convincing ---------------------------------------------
     One full-width gradient band, using the same .icon-panel gradient the
     Promise page uses for its split panels — so the two pages share a
     visual language without the homepage repeating that page's layout.
     This is also the page's strongest colour block, which is why it sits
     here: it marks the turn from "what we sell" to "why us".

     Dark type on the gradient, not white. The Promise page only ever puts
     a decorative icon on .icon-panel and keeps its prose on white, so
     nothing established whether white body text works here — measured, it
     does not: white on these two stops is 2.41:1 and 2.77:1, failing AA
     for both large and normal text. Near-black on the same gradient
     measures 8:1+. The brand colour is untouched; only the ink changed.
------------------------------------------------------------------------ --}}
<section class="icon-panel py-5 my-5">
    <div class="container py-md-4">

        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <p class="section-eyebrow mb-3">The Northlight promise</p>
                <h3 class="mb-0">Care that doesn&rsquo;t make you ask for the basics.</h3>
            </div>
        </div>

        <div class="row g-4 g-lg-5 text-center">
            @foreach($promise as $feature)

            <div class="col-md-4 reveal">
                <div class="d-inline-flex mb-3">
                    @include('partials.icon', ['icon' => $feature['icon'], 'size' => 36])
                </div>
                <h5 class="mb-2">{{ $feature['title'] }}</h5>
                <p class="mb-0">{{ $feature['description'] }}</p>
            </div>

            @endforeach
        </div>

        <div class="text-center mt-5">
            {{-- Dark fill to match the band's ink. .btn-primary is this
                 gradient's own colour and would disappear into it. --}}
            <a href="{{ route('promise') }}" class="btn btn-dark px-4">See what else we promise</a>
        </div>

    </div>
</section>


{{-- 7. Team + testimonial — convincing ----------------------------------
     One section, two movements. The faces and the quote are adjacent on
     purpose: the quote is about Soo-ah, who is in the strip above it, so
     the proof lands on a person the visitor has just met rather than on
     an anonymous practice.
------------------------------------------------------------------------ --}}
<section class="container py-5">

    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <p class="section-eyebrow text-brown mb-3">Who you&rsquo;ll see</p>
            <h3 class="mb-3">A small team, and you&rsquo;ll know them by name.</h3>
            <p class="text-muted mb-0">
                Six people run this practice — three of them here. Between us we speak seven
                languages, and two of us live with the same conditions we treat.
            </p>
        </div>
    </div>

    {{-- Names and roles only. Bios belong to the About page. --}}
    <div class="row justify-content-center g-4 mb-5 text-center">
        @foreach($team as $member)

        <div class="col-6 col-md-4 col-lg-3">
            <div class="bg-brown text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3 reveal reveal-blur"
                style="width: 7rem; height: 7rem; font-size: 2rem; --tilt: {{ $loop->even ? '3deg' : '-3deg' }};">
                {{ $member['initials'] }}
            </div>
            <h6 class="mb-1">{{ $member['name'] }}</h6>
            <p class="small text-muted mb-0">{{ $member['role'] }}</p>
        </div>

        @endforeach
    </div>

    <div class="text-center mb-5">
        <a href="{{ route('about') }}" class="btn btn-outline-primary px-3">Meet the whole team</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <figure class="border-top pt-5 text-center mb-0 reveal">
                <blockquote class="mb-4">
                    <p class="fs-3 mb-0">&ldquo;{{ $testimonial['quote'] }}&rdquo;</p>
                </blockquote>
                <figcaption class="text-muted">
                    <span class="fw-semibold text-body">{{ $testimonial['name'] }}</span>
                    &middot; {{ $testimonial['detail'] }}
                </figcaption>
            </figure>

            <div class="text-center mt-4">
                <a href="{{ route('testimonials') }}" class="text-muted small">Read more patient stories</a>
            </div>
        </div>
    </div>

</section>


{{-- 8. Booking — action -------------------------------------------------
     The page's closing answer to "so what do I do now". Deliberately not
     a form: the Contact page is form-forward, and duplicating it here
     would mean two competing submit targets for the same action.
------------------------------------------------------------------------ --}}
<section class="bg-light py-5 mt-5">
    <div class="container py-md-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- gy-5 g-lg-5 for the same reason as the services row above. --}}
                <div class="row align-items-center gy-5 g-lg-5">

                    <div class="col-md-7">
                        <p class="section-eyebrow text-brown mb-3">Next step</p>
                        <h3 class="mb-3">Book an exam, or just come in and try frames on.</h3>
                        <p class="text-muted mb-4">
                            No appointment needed to browse or to get a frame adjusted. For exams,
                            fittings, and low-vision consultations, pick a time that suits you and
                            we&rsquo;ll confirm by phone.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('contact.create') }}" class="btn btn-primary px-4 py-2">Book an Exam</a>
                            <a href="tel:{{ $business['phone_href'] }}" class="btn btn-outline-primary px-4 py-2">
                                Call {{ $business['phone'] }}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="bg-white rounded-3 p-4 hover-lift">
                            <h6 class="section-eyebrow text-muted mb-3">Opening hours</h6>

                            <dl class="row mb-4 small">
                                @foreach($hours as $entry)
                                <dt class="col-6 fw-normal text-muted">{{ $entry['label'] }}</dt>
                                <dd class="col-6 text-end mb-1 fw-semibold">{{ $entry['value'] }}</dd>
                                @endforeach
                            </dl>

                            <h6 class="section-eyebrow text-muted mb-2">Find us</h6>
                            <address class="small text-muted mb-0">
                                {{ $business['address_line1'] }}<br>
                                {{ $business['address_line2'] }}<br>
                                <a href="{{ $business['maps_url'] }}" target="_blank" rel="noopener noreferrer">
                                    Open in Maps
                                </a>
                            </address>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
