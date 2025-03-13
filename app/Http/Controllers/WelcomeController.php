<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $products = [
            [
                "name" => "Smart Switch (1 Channel)",
                "price" => "Rp 100.000",
                "img" => asset("assets/images/products/onechannel.webp"),
                "description" => "Mau hidup lebih praktis? Dengan Smart Switch 1 Channel ini, kamu bisa menyalakan atau mematikan perangkat listrik di rumah hanya lewat smartphone! Cocok buat kamu yang suka efisiensi dan kenyamanan dalam satu sentuhan.",
                "tags" => ["Smart Switch", "Kendali Lampu"]
            ],
            [
                "name" => "Smart Switch (2 Channel)",
                "price" => "Rp 150.000",
                "img" => asset("assets/images/products/twochannel.jpg"),
                "description" => "Punya lebih dari satu perangkat yang perlu dikendalikan? Smart Switch 2 Channel ini adalah solusi terbaik buat kamu! Bisa atur dua perangkat sekaligus tanpa ribet, tinggal klik di aplikasi. Rumah jadi lebih pintar, hidup makin mudah!",
                "tags" => ["Smart Switch", "Kendali Lampu", "Kendali Kipas Angin"]
            ],
            [
                "name" => "Smart Switch (4 Channel)",
                "price" => "Rp 200.000",
                "img" => asset("assets/images/products/fourchannel.jpg"),
                "description" => "Buat kamu yang ingin rumah lebih modern dan serba otomatis, Smart Switch 4 Channel ini wajib banget kamu punya! Bisa mengontrol empat perangkat sekaligus, hemat listrik, dan pastinya bikin hidup lebih praktis. Cukup sentuh layar ponsel, semuanya bisa diatur dengan mudah!",
                "tags" => ["Smart Switch", "Kendali Lampu", "Kendali Kipas Angin", "Kendali Pompa Air"]
            ],
            [
                "name" => "Smart Switch (8 Channel)",
                "price" => "Rp 350.000",
                "img" => asset("assets/images/products/eightchannel.jpg"),
                "description" => "Ini dia solusi pintar buat rumah canggih! Dengan Smart Switch 8 Channel, kamu bisa mengontrol banyak perangkat listrik hanya dalam satu sistem. Gak perlu repot-repot nyalakan satu per satu, cukup pakai aplikasi dan semuanya bisa kamu kendalikan dari jarak jauh",
                "tags" => ["Smart Switch", "Kendali Lampu", "Kendali Kipas Angin", "Kendali Pompa Air"]
            ]
        ];
        return view('welcome', compact('products'));
    }
}
