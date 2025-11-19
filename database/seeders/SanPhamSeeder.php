<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SanPham;

class SanPhamSeeder extends Seeder
{
    public function run()
    {
        SanPham::insert([
             [
                'ten_san_pham' => ' Bánh bông lan trứng muối',
                'mo_ta' => 'Bánh bông lan mềm mịn, thơm ngon với trứng muối béo ngậy',
                'don_gia' => 25000,
                'ton_kho' => 30,
                'url_hinh_anh' => '/images/food/banhkeo/1.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Bánh mì ngọt',
                'mo_ta' => 'Bánh mì ngọt thơm ngon, mềm mịn',
                'don_gia' => 10000,
                'ton_kho' => 20,
                'url_hinh_anh' => '/images/food/banhkeo/2.png',
                'ma_danh_muc' => 1
            ],
            [
                'ten_san_pham' => ' Bánh lỗ tai heo',
                'mo_ta' => 'Bánh giòn tan, đậm đà hương vị',
                'don_gia' => 12000,
                'ton_kho' => 15,
                'url_hinh_anh' => '/images/food/banhkeo/3.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Bánh đậu xanh',
                'mo_ta' => 'Bánh đậu xanh truyền thống, ngọt dịu',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/banhkeo/4.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Bánh lá dứa nhân đậu xanh',
                'mo_ta' => 'Bánh lá dứa dẻo, thơm ngon, nhân đậu xanh bùi bùi',
                'don_gia' => 18000,
                'ton_kho' => 15,
                'url_hinh_anh' => '/images/food/banhkeo/5.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Kẹo thập cẩm',
                'mo_ta' => 'Kẹo ngọt ngào, đa dạng hương vị',
                'don_gia' => 35000,
                'ton_kho' => 45,
                'url_hinh_anh' => '/images/food/banhkeo/6.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Kẹo bốn mùa',
                'mo_ta' => 'Kẹo mang đậm chất hương vị bốn mùa',
                'don_gia' => 20000,
                'ton_kho' => 20,
                'url_hinh_anh' => '/images/food/banhkeo/7.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Kẹo cà phê',
                'mo_ta' => 'Kẹo mang hương vị cà phê đậm đà',
                'don_gia' => 20000,
                'ton_kho' => 25,
                'url_hinh_anh' => '/images/food/banhkeo/8.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => 'Kẹo me chua ngọt',
                'mo_ta' => 'Kẹo me với vị chua ngọt hấp dẫn',
                'don_gia' => 15000,
                'ton_kho' => 15,
                'url_hinh_anh' => '/images/food/banhkeo/9.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => 'Kẹo HỌNG',
                'mo_ta' => 'Kẹo giúp làm dịu cổ họng, hương vị quất mật ong giúp dễ chịu',
                'don_gia' => 20000,
                'ton_kho' => 25,
                'url_hinh_anh' => '/images/food/banhkeo/10.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Socola HERSEY\'S',
                'mo_ta' => 'Socola hảo hạng, vị ngọt đậm đà',
                'don_gia' => 35000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/banhkeo/13.png',
                'ma_danh_muc' => 1
            ],
            [
                'ten_san_pham' => ' Socola MAROUBAR',
                'mo_ta' => 'Socola thơm ngon, tan chảy trong miệng',
                'don_gia' => 25000,
                'ton_kho' => 60,
                'url_hinh_anh' => '/images/food/banhkeo/16.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Ferrero Rocher',
                'mo_ta' => 'Socola hảo hạng, vị ngọt đậm đà',
                'don_gia' => 45000,
                'ton_kho' => 70,
                'url_hinh_anh' => '/images/food/banhkeo/17.png',
                'ma_danh_muc' => 1
            ],
             [
                'ten_san_pham' => ' Bánh rán DORAEMON',
                'mo_ta' => 'Socola thơm ngon, tan chảy trong miệng',
                'don_gia' => 25000,
                'ton_kho' => 60,
                'url_hinh_anh' => '/images/food/banhkeo/23.png',
                'ma_danh_muc' => 1
            ],
            [
                'ten_san_pham' => 'Kem chè Thái',
                'mo_ta' => 'Món kem mát lạnh với hương vị đặc trưng của Thái Lan',
                'don_gia' => 10000,
                'ton_kho' => 90,
                'url_hinh_anh' => '/images/food/kem/1.png',
                'ma_danh_muc' => 6
            ],
            [
                'ten_san_pham' => 'Kem Marino vị Cacao Socola',
                'mo_ta' => 'Kem mát lạnh với hương vị cacao socola thơm ngon',
                'don_gia' => 10000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/3.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Kem Marino vị Sầu Riêng',
                'mo_ta' => 'Kem mát lạnh với hương vị sầu riêng đặc trưng',
                'don_gia' => 10000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/4.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Kem vị Xoài xanh muối ớt',
                'mo_ta' => 'Kem mát lạnh với hương vị xoài xanh muối ớt độc đáo',
                'don_gia' => 10000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/6.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Kem Marino Cutie Bear',
                'mo_ta' => 'Kem mát lạnh hình gấu dễ thương',
                'don_gia' => 10000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/9.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Kem Marino vị dâu',
                'mo_ta' => 'Kem mát lạnh với hương vị dâu thơm ngon',
                'don_gia' => 10000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/10.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Kem Marino dạng hộp',
                'mo_ta' => 'Kem mát lạnh đóng hộp tiện lợi, nhiều hương vị',
                'don_gia' => 50000,
                'ton_kho' => 50,
                'url_hinh_anh' => '/images/food/kem/15.png',
                'ma_danh_muc' => 6
            ],
             [
                'ten_san_pham' => 'Mì ăn liền 3 miền',
                'mo_ta' => 'Mì ăn liền thơm ngon, tiện lợi cho bữa ăn nhanh',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/1.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì ăn liền Hảo Hảo',
                'mo_ta' => 'Mì ăn liền thơm ngon, tiện lợi cho bữa ăn nhanh',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/2.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì xào ăn liền Hảo Hảo',
                'mo_ta' => 'Mì xào ăn liền thơm ngon, tiện lợi cho bữa ăn nhanh',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/3.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì xào ăn liền Hảo Hảo',
                'mo_ta' => 'Mì xào ăn liền tiện lợi cho bữa ăn nhanh',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/4.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì ăn liền KOKOMI',
                'mo_ta' => 'Mì mang hương vị tôm chua cay đặc trưng',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/4.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì ăn liền 3 miền Gold',
                'mo_ta' => 'Mì thơm ngon, mang hương vị bò hầm đặc trưng',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/5.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì ăn liền Cung đình',
                'mo_ta' => 'Mì ăn liền với hương vị khoai tây cay đặc trưng',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/7.png',
                'ma_danh_muc' => 4
             ],
               [
                'ten_san_pham' => 'Mì Hàn Quốc Koreno',
                'mo_ta' => 'Mì Hàn Quốc với hương vị đặc trưng, cay nồng',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/8.png',
                'ma_danh_muc' => 4
             ],
             [
                'ten_san_pham' => 'Hủ tiếu Miliket',
                'mo_ta' => 'Hủ tiếu thơm ngon, tiện lợi cho bữa ăn nhanh',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/9.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Hủ tiếu Như Ý',
                'mo_ta' => 'Hủ tiếu với nhiều hương vị đặc trưng, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/10.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Hủ tiếu Nam Vang',
                'mo_ta' => 'Hủ tiếu đặc trung Nam Vang',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/11.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Mì Omachi dạng lẩu tự sôi',
                'mo_ta' => 'Tiện lợi và rất nhiều phần bổ dưỡng',
                'don_gia' => 75000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/13.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Mì TOMYUM Bangkok',
                'mo_ta' => 'Hương vị Tomyum đặc trưng của Thái Lan',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/14.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Mì TOMYUM Bangkok',
                'mo_ta' => 'Hương vị Tomyum đặc trưng của Thái Lan',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/14.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Mì TOMYUM Bangkok',
                'mo_ta' => 'Hương vị Tomyum đặc trưng của Thái Lan',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/mi/14.png',
                'ma_danh_muc' => 4
             ],
              [
                'ten_san_pham' => 'Khoai tây chiên',
                'mo_ta' => 'Hương vị khoai tây đậm đà và giòn tan',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/snack/1.png',
                'ma_danh_muc' => 2
             ],
              [
                'ten_san_pham' => 'Bánh tráng nướng',
                'mo_ta' => 'Bánh tráng nướng giòn rụm với hương vị đặc trưng',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/snack/2.png',
                'ma_danh_muc' => 2
             ],
             [
                'ten_san_pham' => 'Nem chua rán',
                'mo_ta' => 'Nem chua rán giòn rụm với hương vị đặc trưng',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/snack/3.png',
                'ma_danh_muc' => 2
             ],
              [
                'ten_san_pham' => 'Xúc xích',
                'mo_ta' => 'Xúc xích thơm ngon, đậm đà hương vị',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/snack/4.png',
                'ma_danh_muc' => 2
             ],
              [
                'ten_san_pham' => 'Bánh tráng cuốn',
                'mo_ta' => 'Bánh tráng cuốn thơm ngon, đậm đà hương vị',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/snack/5.png',
                'ma_danh_muc' => 2
             ],
              [
                'ten_san_pham' => 'Coca-Cola',
                'mo_ta' => 'Nước ngọt Coca-Cola sảng khoái',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/1.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Pepsi',
                'mo_ta' => 'Nước ngọt Pepsi sảng khoái',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/2.png',
                'ma_danh_muc' => 3
             ],
               [
                'ten_san_pham' => 'Red Bull',
                'mo_ta' => 'Nước ngọt Red Bull tăng lực sảng khoái',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/3.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Mirinda Soda Kem',
                'mo_ta' => 'Nước ngọt Mirinda Soda Kem bùng nổ vị giác',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/4.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Sting',
                'mo_ta' => 'Nước ngọt Sting tăng lực sảng khoái',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/5.png',
                'ma_danh_muc' => 3
             ],
                [
                'ten_san_pham' => 'Sprite',
                'mo_ta' => 'Nước ngọt Sprite với hương vị tươi mát',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/6.png',
                'ma_danh_muc' => 3
             ],
                  [
                'ten_san_pham' => 'Warrior',
                'mo_ta' => 'Nước ngọt Warrior vị nho thơm',
                'don_gia' => 10000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/7.png',
                'ma_danh_muc' => 3
             ],
                [
                'ten_san_pham' => 'Trà sữa Matcha Latte',
                'mo_ta' => 'Trà sữa Matcha Latte thơm ngon, béo ngậy',
                'don_gia' => 20000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/8.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Sữa tươi trân châu đường đen',
                'mo_ta' => 'Sữa tươi trân châu đường đen thơm ngon, béo ngậy',
                'don_gia' => 20000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/9.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Nước ép đào',
                'mo_ta' => 'Nước ép đào thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/10.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Nước ép xoài',
                'mo_ta' => 'Nước ép xoài thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/11.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Nước ép nho',
                'mo_ta' => 'Nước ép nho thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/12.png',
                'ma_danh_muc' => 3
             ],
             [
                'ten_san_pham' => 'Nước ép cam, cà rốt',
                'mo_ta' => 'Nước ép cam, cà rốt thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/13.png',
                'ma_danh_muc' => 3
             ],
             [
                'ten_san_pham' => 'Nước ép dưa hấu',
                'mo_ta' => 'Nước ép dưa hấu thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/14.png',
                'ma_danh_muc' => 3
             ],
             [
                'ten_san_pham' => 'Nước ép cam',
                'mo_ta' => 'Nước ép cam thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/15.png',
                'ma_danh_muc' => 3
             ],
             [
                'ten_san_pham' => 'Nước ép dứa',
                'mo_ta' => 'Nước ép dứa thơm ngon, tươi mát',
                'don_gia' => 25000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/16.png',
                'ma_danh_muc' => 3
             ],
             [
                'ten_san_pham' => 'Trà tắc',
                'mo_ta' => 'Trà tắc thơm, tươi mát',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/17.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Trà chanh',
                'mo_ta' => 'Trà chanh thơm, tươi mát',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/18.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Trà đào cam sả',
                'mo_ta' => 'Trà đào cam sả mang hương vị mùa hè tươi mát',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/nuoc/19.png',
                'ma_danh_muc' => 3
             ],
              [
                'ten_san_pham' => 'Mít sấy',
                'mo_ta' => 'Mít sấy giòn, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/1.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Sầu riêng sấy lạnh',
                'mo_ta' => 'Sầu riêng sấy lạnh giòn, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/2.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Chuối sấy',
                'mo_ta' => 'Chuối sấy giòn, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/3.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Xoài sấy dẻo',
                'mo_ta' => 'Xoài sấy dẻo, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/4.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Mãng cầu sấy dẻo',
                'mo_ta' => 'Mãng cầu sấy dẻo, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/7.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Mận sấy dẻo',
                'mo_ta' => 'Mận sấy dẻo, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/8.png',
                'ma_danh_muc' => 5
             ],
              [
                'ten_san_pham' => 'Trái cây sấy hỗn hợp',
                'mo_ta' => 'Trái cây sấy hỗn hợp, thơm ngon',
                'don_gia' => 15000,
                'ton_kho' => 100,
                'url_hinh_anh' => '/images/food/traicay/9.png',
                'ma_danh_muc' => 5
             ]
        ]);
    }
}
