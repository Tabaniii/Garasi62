<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Http;

class FooterSettingController extends Controller
{
    public function fetchOgData(Request $request)
    {
        $url = $request->input('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['success' => false, 'message' => 'Invalid URL']);
        }

        try {
            // Fetch URL content with a timeout
            $response = Http::timeout(5)->get($url);

            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Failed to fetch URL']);
            }

            $html = $response->body();

            // Extract OG Site Name or Title
            $siteName = null;

            // Try og:site_name
            if (preg_match('/<meta[^>]*property=["\']og:site_name["\'][^>]*content=["\']([^"\']*)["\']/i', $html, $matches)) {
                $siteName = $matches[1];
            }

            // Try og:title if no site_name
            if (!$siteName && preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']*)["\']/i', $html, $matches)) {
                $siteName = $matches[1];
            }

            // Try <title> tag as fallback
            if (!$siteName && preg_match('/<title[^>]*>(.*?)<\/title>/i', $html, $matches)) {
                $siteName = $matches[1];
            }

            // Map site name to FontAwesome icon
            $icon = 'link'; // Default
            $platform = $siteName ?? 'Unknown';

            $siteNameLower = strtolower($siteName ?? $url);

            $iconMap = [
                'facebook' => 'facebook',
                'twitter' => 'twitter',
                'x.com' => 'twitter',
                'instagram' => 'instagram',
                'linkedin' => 'linkedin',
                'youtube' => 'youtube',
                'tiktok' => 'tiktok',
                'pinterest' => 'pinterest',
                'github' => 'github',
                'whatsapp' => 'whatsapp',
                'telegram' => 'telegram',
                'skype' => 'skype',
                'google' => 'google',
            ];

            foreach ($iconMap as $key => $faIcon) {
                if (str_contains($siteNameLower, $key)) {
                    $icon = $faIcon;
                    // Refine platform name if we found a match
                    if ($platform === 'Unknown' || str_contains(strtolower($platform), 'http')) {
                        $platform = ucfirst($key === 'x.com' ? 'Twitter' : $key);
                    }
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'platform' => $platform,
                'icon' => $icon
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit()
    {
        $brands = car::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        $selectedBrands = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", SiteSetting::get('footer_brands_list', ''))));
        $selectedBrands = collect($selectedBrands)->filter(function ($brand) use ($brands) {
            return $brands->contains($brand);
        })->values()->all();

        $operationalHours = $this->getOperationalHours();
        $showrooms = $this->getShowrooms();
        $socialLinks = $this->getSocialLinks();

        $settings = [
            'site_name' => SiteSetting::get('site_name', 'GARASI62'),
            'site_email' => SiteSetting::get('site_email', SiteSetting::get('footer_email', 'Colorlib@gmail.com')),
            'site_operational_hours' => SiteSetting::get('site_operational_hours', 'Sales: 08:00 am to 18:00 pm'),
            'site_logo' => SiteSetting::get('site_logo', 'garasi62/img/ride62-fix.svg'),
            'site_favicon' => SiteSetting::get('site_favicon', 'favicon.ico'),
            'contact_hours_weekday' => SiteSetting::get('contact_hours_weekday', '08:00 am to 18:00 pm'),
            'contact_hours_saturday' => SiteSetting::get('contact_hours_saturday', '10:00 am to 16:00 pm'),
            'contact_hours_sunday' => SiteSetting::get('contact_hours_sunday', 'Closed'),
            'showroom_1_title' => SiteSetting::get('showroom_1_title', 'California Showroom'),
            'showroom_1_address' => SiteSetting::get('showroom_1_address', '625 Gloria Union, California, United Stated'),
            'showroom_1_phone' => SiteSetting::get('showroom_1_phone', '(+12) 456 678 9100'),
            'showroom_2_title' => SiteSetting::get('showroom_2_title', 'New York Showroom'),
            'showroom_2_address' => SiteSetting::get('showroom_2_address', '8235 South Ave. Jamestown, NewYork'),
            'showroom_2_phone' => SiteSetting::get('showroom_2_phone', '(+12) 456 678 9100'),
            'showroom_3_title' => SiteSetting::get('showroom_3_title', 'Florida Showroom'),
            'showroom_3_address' => SiteSetting::get('showroom_3_address', '497 Beaver Ridge St. Daytona Beach, Florida'),
            'showroom_3_phone' => SiteSetting::get('showroom_3_phone', '(+12) 456 678 9100'),
            'contact_title' => SiteSetting::get('footer_contact_title', 'Contact Us Now!'),
            'phone' => SiteSetting::get('footer_phone', '(+12) 345 678 910'),
            'about_text' => SiteSetting::get('footer_about_text', 'Any questions? Let us know in store at 625 Gloria Union, California, United Stated or call us on (+1) 96 123 8888'),
            'facebook_url' => SiteSetting::get('footer_facebook_url', ''),
            'twitter_url' => SiteSetting::get('footer_twitter_url', ''),
            'google_url' => SiteSetting::get('footer_google_url', ''),
            'instagram_url' => SiteSetting::get('footer_instagram_url', ''),
            'skype_url' => SiteSetting::get('footer_skype_url', ''),
            'info1_title' => SiteSetting::get('footer_info1_title', 'Information'),
            'info1_list' => SiteSetting::get('footer_info1_list', "Purchase|\nPayment|\nShipping|\nReturn|"),
            'info2_title' => SiteSetting::get('footer_info2_title', 'Information'),
            'info2_list' => SiteSetting::get('footer_info2_list', "Hatchback|\nSedan|\nSUV|\nCrossover|"),
            'brands_title' => SiteSetting::get('footer_brands_title', 'Top Brand'),
            'brands_list' => SiteSetting::get('footer_brands_list', "Abarth|\nAcura|\nAlfa Romeo|\nAudi|\nBMW|\nChevrolet|\nFerrari|\nHonda|"),
            'about_title' => SiteSetting::get('about_title', 'Wellcome To HVAC Auto Online'),
            'about_subtitle' => SiteSetting::get('about_subtitle', 'We Provide Everything You Need To A Car'),
            'about_description' => SiteSetting::get('about_description', 'First I will explain what contextual advertising is. Contextual advertising means the advertising of products on a website according to the content the page is displaying. For example if the content of a website was information on a Ford truck then the advertisements'),
            'about_feature_1_title' => SiteSetting::get('about_feature_1_title', 'Quality Assurance System'),
            'about_feature_1_text' => SiteSetting::get('about_feature_1_text', 'It seems though that some of the biggest problems with the internet advertising trends are the lack of'),
            'about_feature_1_icon' => SiteSetting::get('about_feature_1_icon', 'img/about/af-1.png'),
            'about_feature_2_title' => SiteSetting::get('about_feature_2_title', 'Accurate Testing Processes'),
            'about_feature_2_text' => SiteSetting::get('about_feature_2_text', 'Where do you register your complaints? How can you protest in any form against companies whose'),
            'about_feature_2_icon' => SiteSetting::get('about_feature_2_icon', 'img/about/af-2.png'),
            'about_feature_3_title' => SiteSetting::get('about_feature_3_title', 'Infrastructure Integration Technology'),
            'about_feature_3_text' => SiteSetting::get('about_feature_3_text', 'So in final analysis: it’s true, I hate peeping Toms, but if I had to choose, I’d take one any day over an'),
            'about_feature_3_icon' => SiteSetting::get('about_feature_3_icon', 'img/about/af-3.png'),
            'about_image' => SiteSetting::get('about_image', 'img/about/about-pic.jpg'),
            'about_mission_title' => SiteSetting::get('about_mission_title', 'Our Mission'),
            'about_mission_text' => SiteSetting::get('about_mission_text', 'Now, I’m not like Robin, that weirdo from my cultural anthropology class; I think that advertising is something that has its place in our society; which for better or worse is structured along a marketplace economy. But, simply because I feel advertising has a right to exist, doesn’t mean that I like or agree with it, in its'),
            'about_vision_title' => SiteSetting::get('about_vision_title', 'Our Vision'),
            'about_vision_text' => SiteSetting::get('about_vision_text', 'Where do you register your complaints? How can you protest in any form against companies whose advertising techniques you don’t agree with? You don’t. And on another point of difference between traditional products and their advertising and those of the internet nature, simply ignoring internet advertising is'),
        ];

        return view('admin.footer.edit', compact('settings', 'brands', 'selectedBrands', 'operationalHours', 'showrooms', 'socialLinks'));
    }

    public function update(Request $request)
    {
        $allowedBrands = car::distinct()->whereNotNull('brand')->pluck('brand')->values()->toArray();
        $brandArrayRule = empty($allowedBrands) ? ['nullable', 'array', 'max:4'] : ['required', 'array', 'min:1', 'max:4'];
        $brandItemRule = empty($allowedBrands) ? ['string'] : ['string', Rule::in($allowedBrands)];

        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_email' => ['required', 'email', 'max:100'],
            'site_operational_hours' => ['required', 'string', 'max:120'],
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'site_favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,ico', 'max:2048'],

            // Dynamic fields
            'operational_hours' => ['nullable', 'array'],
            'operational_hours.*.day' => ['required', 'string', 'max:50'],
            'operational_hours.*.hours' => ['required', 'string', 'max:50'],
            'showrooms' => ['nullable', 'array'],
            'showrooms.*.title' => ['required', 'string', 'max:120'],
            'showrooms.*.address' => ['required', 'string', 'max:255'],
            'showrooms.*.phone' => ['required', 'string', 'max:50'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.platform' => ['required', 'string', 'max:50'],
            'social_links.*.icon' => ['required', 'string', 'max:50'],
            'social_links.*.url' => ['required', 'url', 'max:255'],

            'contact_title' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:50'],
            'about_text' => ['required', 'string', 'max:500'],
            // Legacy social fields removed from validation as we use social_links now
            'info1_title' => ['required', 'string', 'max:100'],
            'info1_list' => ['required', 'string', 'max:2000'],
            'info2_title' => ['required', 'string', 'max:100'],
            'info2_list' => ['required', 'string', 'max:2000'],
            'brands_title' => ['required', 'string', 'max:100'],
            'brands' => $brandArrayRule,
            'brands.*' => $brandItemRule,
            'about_title' => ['required', 'string', 'max:120'],
            'about_subtitle' => ['required', 'string', 'max:140'],
            'about_description' => ['required', 'string', 'max:1000'],
            'about_feature_1_title' => ['required', 'string', 'max:120'],
            'about_feature_1_text' => ['required', 'string', 'max:400'],
            'about_feature_2_title' => ['required', 'string', 'max:120'],
            'about_feature_2_text' => ['required', 'string', 'max:400'],
            'about_feature_3_title' => ['required', 'string', 'max:120'],
            'about_feature_3_text' => ['required', 'string', 'max:400'],
            'about_mission_title' => ['required', 'string', 'max:120'],
            'about_mission_text' => ['required', 'string', 'max:1000'],
            'about_vision_title' => ['required', 'string', 'max:120'],
            'about_vision_text' => ['required', 'string', 'max:1000'],
            'about_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'about_feature_1_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'about_feature_2_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'about_feature_3_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $payload = [];
        foreach ($data as $key => $value) {
            if (is_array($value))
                continue;
            $payload[$key] = is_string($value) ? trim($value) : $value;
        }

        SiteSetting::set('site_name', $payload['site_name']);
        SiteSetting::set('site_email', $payload['site_email']);
        SiteSetting::set('site_operational_hours', $payload['site_operational_hours']);

        // Save dynamic arrays
        $this->saveOperationalHours(array_values($request->input('operational_hours', [])));
        $this->saveShowrooms(array_values($request->input('showrooms', [])));
        $this->saveSocialLinks(array_values($request->input('social_links', [])));

        SiteSetting::set('footer_contact_title', $payload['contact_title']);
        SiteSetting::set('footer_phone', $payload['phone']);
        SiteSetting::set('footer_email', $payload['site_email']);
        SiteSetting::set('footer_about_text', $payload['about_text']);

        // Legacy fields update for backward compatibility (optional, but good for safety)
        // We will just clear them or leave them as is since we use social_links now.
        // But to be safe, if we have social links that match legacy keys, we could update them.
        // For now, we will rely on getSocialLinks migration.

        SiteSetting::set('footer_info1_title', $payload['info1_title']);
        SiteSetting::set('footer_info1_list', $payload['info1_list']);
        SiteSetting::set('footer_info2_title', $payload['info2_title']);
        SiteSetting::set('footer_info2_list', $payload['info2_list']);
        SiteSetting::set('footer_brands_title', $payload['brands_title']);

        $brandList = $request->input('brands', []);
        $brandList = array_values(array_unique(array_filter($brandList)));
        SiteSetting::set('footer_brands_list', implode("\n", $brandList));

        SiteSetting::set('about_title', $payload['about_title']);
        SiteSetting::set('about_subtitle', $payload['about_subtitle']);
        SiteSetting::set('about_description', $payload['about_description']);
        SiteSetting::set('about_feature_1_title', $payload['about_feature_1_title']);
        SiteSetting::set('about_feature_1_text', $payload['about_feature_1_text']);
        SiteSetting::set('about_feature_2_title', $payload['about_feature_2_title']);
        SiteSetting::set('about_feature_2_text', $payload['about_feature_2_text']);
        SiteSetting::set('about_feature_3_title', $payload['about_feature_3_title']);
        SiteSetting::set('about_feature_3_text', $payload['about_feature_3_text']);
        SiteSetting::set('about_mission_title', $payload['about_mission_title']);
        SiteSetting::set('about_mission_text', $payload['about_mission_text']);
        SiteSetting::set('about_vision_title', $payload['about_vision_title']);
        SiteSetting::set('about_vision_text', $payload['about_vision_text']);

        if ($request->hasFile('site_logo')) {
            SiteSetting::set('site_logo', media()->upload($request->file('site_logo'), 'site'));
        }
        if ($request->hasFile('site_favicon')) {
            SiteSetting::set('site_favicon', media()->upload($request->file('site_favicon'), 'site'));
        }
        if ($request->hasFile('about_image')) {
            SiteSetting::set('about_image', media()->upload($request->file('about_image'), 'about'));
        }
        if ($request->hasFile('about_feature_1_icon')) {
            SiteSetting::set('about_feature_1_icon', media()->upload($request->file('about_feature_1_icon'), 'about'));
        }
        if ($request->hasFile('about_feature_2_icon')) {
            SiteSetting::set('about_feature_2_icon', media()->upload($request->file('about_feature_2_icon'), 'about'));
        }
        if ($request->hasFile('about_feature_3_icon')) {
            SiteSetting::set('about_feature_3_icon', media()->upload($request->file('about_feature_3_icon'), 'about'));
        }

        return redirect()->route('admin.footer.edit')->with('success', 'Site setting berhasil diperbarui.');
    }

    private function getOperationalHours()
    {
        $hours = SiteSetting::get('operational_hours', '');
        if (empty($hours)) {
            return [
                ['day' => 'Weekday', 'hours' => '08:00 am to 18:00 pm'],
                ['day' => 'Saturday', 'hours' => '10:00 am to 16:00 pm'],
                ['day' => 'Sunday', 'hours' => 'Closed'],
            ];
        }
        return json_decode($hours, true) ?? [];
    }

    private function saveOperationalHours(array $hours)
    {
        SiteSetting::set('operational_hours', json_encode($hours));
    }

    private function getShowrooms()
    {
        $showrooms = SiteSetting::get('showrooms', '');
        if (empty($showrooms)) {
            return [
                [
                    'title' => 'California Showroom',
                    'address' => '625 Gloria Union, California, United Stated',
                    'phone' => '(+12) 456 678 9100'
                ],
                [
                    'title' => 'New York Showroom',
                    'address' => '8235 South Ave. Jamestown, NewYork',
                    'phone' => '(+12) 456 678 9100'
                ],
                [
                    'title' => 'Florida Showroom',
                    'address' => '497 Beaver Ridge St. Daytona Beach, Florida',
                    'phone' => '(+12) 456 678 9100'
                ]
            ];
        }
        return json_decode($showrooms, true) ?? [];
    }

    private function saveShowrooms(array $showrooms)
    {
        SiteSetting::set('showrooms', json_encode($showrooms));
    }

    private function getSocialLinks()
    {
        $socialLinks = SiteSetting::get('footer_social_links', '');
        if (!empty($socialLinks)) {
            return json_decode($socialLinks, true) ?? [];
        }

        // Migration from legacy fields
        $legacyLinks = [];
        $platforms = [
            'facebook' => ['icon' => 'facebook', 'key' => 'footer_facebook_url'],
            'twitter' => ['icon' => 'twitter', 'key' => 'footer_twitter_url'],
            'google' => ['icon' => 'google', 'key' => 'footer_google_url'],
            'instagram' => ['icon' => 'instagram', 'key' => 'footer_instagram_url'],
            'skype' => ['icon' => 'skype', 'key' => 'footer_skype_url'],
        ];

        foreach ($platforms as $name => $config) {
            $url = SiteSetting::get($config['key'], '');
            if (!empty($url)) {
                $legacyLinks[] = [
                    'platform' => ucfirst($name),
                    'icon' => $config['icon'],
                    'url' => $url
                ];
            }
        }

        // If absolutely nothing exists, return empty array (or maybe some defaults if desired)
        if (empty($legacyLinks)) {
            return [
                ['platform' => 'Facebook', 'icon' => 'facebook', 'url' => ''],
                ['platform' => 'Instagram', 'icon' => 'instagram', 'url' => ''],
                ['platform' => 'Twitter', 'icon' => 'twitter', 'url' => ''],
                ['platform' => 'Google', 'icon' => 'google', 'url' => ''],
                ['platform' => 'Skype', 'icon' => 'skype', 'url' => ''],
            ];
        }

        return $legacyLinks;
    }

    private function saveSocialLinks(array $links)
    {
        SiteSetting::set('footer_social_links', json_encode($links));
    }
}
