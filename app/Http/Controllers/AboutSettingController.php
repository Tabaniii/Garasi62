<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'title' => SiteSetting::get('about_title', 'Wellcome To HVAC Auto Online'),
            'subtitle' => SiteSetting::get('about_subtitle', 'We Provide Everything You Need To A Car'),
            'description' => SiteSetting::get('about_description', 'First I will explain what contextual advertising is. Contextual advertising means the advertising of products on a website according to the content the page is displaying. For example if the content of a website was information on a Ford truck then the advertisements'),
            'feature_1_title' => SiteSetting::get('about_feature_1_title', 'Quality Assurance System'),
            'feature_1_text' => SiteSetting::get('about_feature_1_text', 'It seems though that some of the biggest problems with the internet advertising trends are the lack of'),
            'feature_1_icon' => SiteSetting::get('about_feature_1_icon', 'img/about/af-1.png'),
            'feature_2_title' => SiteSetting::get('about_feature_2_title', 'Accurate Testing Processes'),
            'feature_2_text' => SiteSetting::get('about_feature_2_text', 'Where do you register your complaints? How can you protest in any form against companies whose'),
            'feature_2_icon' => SiteSetting::get('about_feature_2_icon', 'img/about/af-2.png'),
            'feature_3_title' => SiteSetting::get('about_feature_3_title', 'Infrastructure Integration Technology'),
            'feature_3_text' => SiteSetting::get('about_feature_3_text', 'So in final analysis: it’s true, I hate peeping Toms, but if I had to choose, I’d take one any day over an'),
            'feature_3_icon' => SiteSetting::get('about_feature_3_icon', 'img/about/af-3.png'),
            'image' => SiteSetting::get('about_image', 'img/about/about-pic.jpg'),
            'mission_title' => SiteSetting::get('about_mission_title', 'Our Mission'),
            'mission_text' => SiteSetting::get('about_mission_text', 'Now, I’m not like Robin, that weirdo from my cultural anthropology class; I think that advertising is something that has its place in our society; which for better or worse is structured along a marketplace economy. But, simply because I feel advertising has a right to exist, doesn’t mean that I like or agree with it, in its'),
            'vision_title' => SiteSetting::get('about_vision_title', 'Our Vision'),
            'vision_text' => SiteSetting::get('about_vision_text', 'Where do you register your complaints? How can you protest in any form against companies whose advertising techniques you don’t agree with? You don’t. And on another point of difference between traditional products and their advertising and those of the internet nature, simply ignoring internet advertising is'),
        ];

        return view('admin.about.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'max:1000'],
            'feature_1_title' => ['required', 'string', 'max:120'],
            'feature_1_text' => ['required', 'string', 'max:400'],
            'feature_2_title' => ['required', 'string', 'max:120'],
            'feature_2_text' => ['required', 'string', 'max:400'],
            'feature_3_title' => ['required', 'string', 'max:120'],
            'feature_3_text' => ['required', 'string', 'max:400'],
            'mission_title' => ['required', 'string', 'max:120'],
            'mission_text' => ['required', 'string', 'max:1000'],
            'vision_title' => ['required', 'string', 'max:120'],
            'vision_text' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'feature_1_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'feature_2_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'feature_3_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $payload = [];
        foreach ($data as $key => $value) {
            $payload[$key] = is_string($value) ? trim($value) : $value;
        }

        SiteSetting::set('about_title', $payload['title']);
        SiteSetting::set('about_subtitle', $payload['subtitle']);
        SiteSetting::set('about_description', $payload['description']);
        SiteSetting::set('about_feature_1_title', $payload['feature_1_title']);
        SiteSetting::set('about_feature_1_text', $payload['feature_1_text']);
        SiteSetting::set('about_feature_2_title', $payload['feature_2_title']);
        SiteSetting::set('about_feature_2_text', $payload['feature_2_text']);
        SiteSetting::set('about_feature_3_title', $payload['feature_3_title']);
        SiteSetting::set('about_feature_3_text', $payload['feature_3_text']);
        SiteSetting::set('about_mission_title', $payload['mission_title']);
        SiteSetting::set('about_mission_text', $payload['mission_text']);
        SiteSetting::set('about_vision_title', $payload['vision_title']);
        SiteSetting::set('about_vision_text', $payload['vision_text']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about', 'public');
            SiteSetting::set('about_image', 'storage/' . $imagePath);
        }
        if ($request->hasFile('feature_1_icon')) {
            $iconPath = $request->file('feature_1_icon')->store('about', 'public');
            SiteSetting::set('about_feature_1_icon', 'storage/' . $iconPath);
        }
        if ($request->hasFile('feature_2_icon')) {
            $iconPath = $request->file('feature_2_icon')->store('about', 'public');
            SiteSetting::set('about_feature_2_icon', 'storage/' . $iconPath);
        }
        if ($request->hasFile('feature_3_icon')) {
            $iconPath = $request->file('feature_3_icon')->store('about', 'public');
            SiteSetting::set('about_feature_3_icon', 'storage/' . $iconPath);
        }

        return redirect()->route('admin.about.edit')->with('success', 'Konten About Us berhasil diperbarui.');
    }
}
