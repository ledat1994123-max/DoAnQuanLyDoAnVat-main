<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMuc;

class CategoryAdminController extends Controller
{
    // Lấy danh sách danh mục
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh mục thành công',
            'data' => DanhMuc::orderBy('ma_danh_muc','desc')->get()
        ]);
    }

    // Thêm danh mục
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required'
        ]);

        $category = DanhMuc::create([
            'ten_danh_muc' => $request->ten_danh_muc,
            'mota' => $request->mota ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công',
            'data' => $category
        ]);
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = DanhMuc::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $category->update([
            'ten_danh_muc' => $request->ten_danh_muc,
            'mota' => $request->mota
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $category
        ]);
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = DanhMuc::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công'
        ]);
    }
}
