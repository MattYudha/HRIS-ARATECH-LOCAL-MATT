<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterConfiguration;

class LetterConfigurationController extends Controller
{
    public function index()
    {
        $config = LetterConfiguration::first() ?: new LetterConfiguration();
        return view('letter-configurations.index', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string',
            'company_email' => 'nullable|email',
            'company_website' => 'nullable|string',
            'letterhead_footer' => 'nullable|string',
            'letter_number_format' => 'required|string',
        ]);

        $config = LetterConfiguration::first() ?: new LetterConfiguration();
        $config->fill($request->all())->save();
        return redirect()->route('letter-configurations.index')->with('success', 'Configuration updated.');
    }
}
