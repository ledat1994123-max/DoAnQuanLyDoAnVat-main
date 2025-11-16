<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('web.about');
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function contactPost(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string|max:2000',
        ]);

        // Trong thực tế, bạn có thể lưu vào database hoặc gửi email
        // Ở đây chúng ta chỉ trả về thông báo thành công
        
        return redirect()->route('contact')
                        ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}