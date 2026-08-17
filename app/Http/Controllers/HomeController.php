<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * The homepage is a curated overview, not a summary of every page: it
     * carries a deliberate subset of the Services, Promise, Team and
     * Testimonial content and links forward to the full page for each.
     * The subsets are selected by title/name rather than array index so
     * that reordering config/northlight.php can't silently change what
     * the homepage shows — it would fail loudly in pick() instead.
     */
    public function __invoke(Request $request): View
    {
        $featuredProducts = Product::where('is_featured', true)->limit(3)->get();

        // The three core clinical offerings. The remaining three (kids'
        // eyewear, low vision, repairs) are differentiators rather than
        // the basics, and the ones worth previewing here are already
        // represented in the Promise module below.
        $services = $this->pick(config('northlight.services'), 'title', [
            'Comprehensive Eye Exams',
            'Prescription Eyeglasses',
            'Contact Lens Fittings',
        ]);

        // The three the practice actually leads with: physical access,
        // language access, and affordability.
        $promise = $this->pick(config('northlight.promise'), 'title', [
            'Accessible by design',
            'Multilingual staff',
            'Insurance & payment plans',
        ]);

        // One per function — clinical, optical, front desk — so three
        // faces convey the whole practice rather than three optometrists.
        $team = $this->pick(config('northlight.team'), 'name', [
            'Dr. Amara Okafor, OD',
            'Marcus Webb',
            'Soo-ah Kim',
        ]);

        // Deliberately *not* the quote the Testimonials page features, and
        // deliberately the one about Soo-ah — she appears in the team strip
        // directly above it, so the section reads as one thought instead of
        // two adjacent modules.
        $testimonial = Arr::first(
            $this->pick(config('northlight.testimonials'), 'name', ['Daniel P.'])
        );

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'services' => $services,
            'promise' => $promise,
            'team' => $team,
            'testimonial' => $testimonial,
            'business' => config('northlight.business'),
            'hours' => config('northlight.hours'),
        ]);
    }

    /**
     * Pull specific entries out of a config content array, preserving the
     * order the keys were requested in.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<int, string>  $wanted
     * @return array<int, array<string, mixed>>
     *
     * @throws \InvalidArgumentException if an entry no longer exists.
     */
    private function pick(array $items, string $key, array $wanted): array
    {
        return array_map(function (string $value) use ($items, $key) {
            $match = Arr::first($items, fn (array $item) => $item[$key] === $value);

            if ($match === null) {
                throw new \InvalidArgumentException(
                    "Homepage expects a northlight content entry with {$key} \"{$value}\", but none exists. "
                    . 'It was probably renamed in config/northlight.php — update HomeController to match.'
                );
            }

            return $match;
        }, $wanted);
    }
}
