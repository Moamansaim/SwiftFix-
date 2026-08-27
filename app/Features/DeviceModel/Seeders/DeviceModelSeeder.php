<?php

namespace App\Features\DeviceModel\Seeders;

use App\Features\Brand\Models\Brand;
use App\Features\DeviceModel\Models\DeviceModel;
use Illuminate\Database\Seeder;

class DeviceModelSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            // =========================================================
            // Smartphones & Tablets
            // =========================================================

            'Apple' => [
                'iPhone 11',
                'iPhone 12',
                'iPhone 13',
                'iPhone 14',
                'iPhone 15',
                'iPhone 15 Pro',
                'iPhone 16',
                'iPhone 16 Pro',
                'iPhone 16 Pro Max',
                'iPad Pro',
            ],

            'Samsung' => [
                'Galaxy S21',
                'Galaxy S22',
                'Galaxy S23',
                'Galaxy S24',
                'Galaxy S24 Ultra',
                'Galaxy S25',
                'Galaxy A34',
                'Galaxy A54',
                'Galaxy A55',
                'Galaxy Z Fold 6',
            ],

            'Xiaomi' => [
                'Xiaomi 12',
                'Xiaomi 13',
                'Xiaomi 13 Pro',
                'Xiaomi 14',
                'Xiaomi 14 Ultra',
                'Xiaomi 15',
                'Redmi Note 12 Pro',
                'Redmi Note 13 Pro',
                'Redmi Note 14 Pro',
                'Poco X6 Pro',
            ],

            'Huawei' => [
                'P40 Pro',
                'P50 Pro',
                'P60 Pro',
                'Pura 70',
                'Mate 40 Pro',
                'Mate 50 Pro',
                'Mate 60 Pro',
                'Mate 70 Pro',
                'Nova 12 Pro',
                'MatePad Pro',
            ],

            'Honor' => [
                'Honor 50',
                'Honor 70',
                'Honor 90',
                'Honor 200',
                'Honor 200 Pro',
                'Honor X8',
                'Honor X9b',
                'Magic4 Pro',
                'Magic5 Pro',
                'Magic6 Pro',
            ],

            'Oppo' => [
                'Find X3 Pro',
                'Find X5 Pro',
                'Find X6 Pro',
                'Find X7 Ultra',
                'Find X8 Pro',
                'Reno 8 Pro',
                'Reno 10 Pro',
                'Reno 11 Pro',
                'Reno 12 Pro',
                'A79',
            ],

            'Vivo' => [
                'V23',
                'V25',
                'V27',
                'V29',
                'V30 Pro',
                'V40 Pro',
                'X80 Pro',
                'X90 Pro',
                'X100 Pro',
                'X200 Pro',
            ],

            'OnePlus' => [
                'OnePlus 9',
                'OnePlus 10 Pro',
                'OnePlus 11',
                'OnePlus 12',
                'OnePlus 13',
                'OnePlus Nord 2',
                'OnePlus Nord 3',
                'OnePlus Nord 4',
                'OnePlus Nord CE 3',
                'OnePlus Nord CE 4',
            ],

            'Realme' => [
                'Realme 9 Pro',
                'Realme 10 Pro',
                'Realme 11 Pro',
                'Realme 12 Pro+',
                'Realme 13 Pro+',
                'Realme GT 2 Pro',
                'Realme GT 3',
                'Realme GT 5 Pro',
                'Realme GT 6',
                'Realme Narzo 70 Pro',
            ],

            'Motorola' => [
                'Moto G52',
                'Moto G53',
                'Moto G54',
                'Moto G84',
                'Moto G85',
                'Motorola Edge 30',
                'Motorola Edge 40',
                'Motorola Edge 50 Pro',
                'Razr 40 Ultra',
                'Razr 50 Ultra',
            ],

            'Nokia' => [
                'Nokia 5.4',
                'Nokia 6.2',
                'Nokia 7.2',
                'Nokia G20',
                'Nokia G42',
                'Nokia G60',
                'Nokia X10',
                'Nokia X20',
                'Nokia X30',
                'Nokia 2660 Flip',
            ],

            'Sony' => [
                'Xperia 1 III',
                'Xperia 1 IV',
                'Xperia 1 V',
                'Xperia 1 VI',
                'Xperia 5 III',
                'Xperia 5 IV',
                'Xperia 5 V',
                'Xperia 10 IV',
                'Xperia 10 V',
                'Xperia 10 VI',
            ],

            'LG' => [
                'LG G6',
                'LG G7 ThinQ',
                'LG G8 ThinQ',
                'LG V30',
                'LG V40 ThinQ',
                'LG V50 ThinQ',
                'LG V60 ThinQ',
                'LG Velvet',
                'LG Wing',
                'LG K52',
            ],

            'HTC' => [
                'HTC U11',
                'HTC U12+',
                'HTC U19e',
                'HTC U20 5G',
                'HTC Desire 20 Pro',
                'HTC Desire 21 Pro',
                'HTC Desire 22 Pro',
                'HTC U23',
                'HTC U23 Pro',
                'HTC Exodus 1',
            ],

            'Google' => [
                'Pixel 5',
                'Pixel 5a',
                'Pixel 6',
                'Pixel 6 Pro',
                'Pixel 7',
                'Pixel 7 Pro',
                'Pixel 8',
                'Pixel 8 Pro',
                'Pixel 9',
                'Pixel 9 Pro',
            ],

            'ZTE' => [
                'Axon 30',
                'Axon 40 Ultra',
                'Axon 50 Ultra',
                'Axon 60 Ultra',
                'Blade V40',
                'Blade V50',
                'Blade A72',
                'Nubia Z50',
                'Nubia Z60 Ultra',
                'Nubia RedMagic 9 Pro',
            ],

            'TCL' => [
                'TCL 10 5G',
                'TCL 20 5G',
                'TCL 30',
                'TCL 40',
                'TCL 40 NXTPAPER',
                'TCL 50',
                'TCL 50 Pro',
                'TCL 20 SE',
                'TCL 20L',
                'TCL 20 Pro 5G',
            ],

            'Alcatel' => [
                'Alcatel 1',
                'Alcatel 1B',
                'Alcatel 1S',
                'Alcatel 3',
                'Alcatel 3L',
                'Alcatel 3X',
                'Alcatel 5',
                'Alcatel 5V',
                'Alcatel 7',
                'Alcatel 3V',
            ],

            'Asus' => [
                'ROG Phone 5',
                'ROG Phone 6',
                'ROG Phone 7',
                'ROG Phone 8',
                'ROG Phone 9',
                'Zenfone 8',
                'Zenfone 9',
                'Zenfone 10',
                'Zenfone 11',
                'Zenfone 11 Ultra',
            ],

            'Lenovo' => [
                'Legion Phone Duel',
                'Legion Phone Duel 2',
                'K13 Note',
                'K14',
                'K15 Plus',
                'Tab M10',
                'Tab M11',
                'Tab P11',
                'Tab P12',
                'Tab Extreme',
            ],

            'Tecno' => [
                'Camon 18',
                'Camon 19',
                'Camon 20',
                'Camon 20 Pro',
                'Camon 30 Pro',
                'Phantom X2',
                'Phantom V Fold',
                'Phantom V Flip',
                'Spark 20 Pro',
                'Spark 30 Pro',
            ],

            'Infinix' => [
                'Hot 11',
                'Hot 12',
                'Hot 30',
                'Hot 40 Pro',
                'Hot 50 Pro',
                'Note 12',
                'Note 30 Pro',
                'Note 40 Pro',
                'GT 20 Pro',
                'Zero 30',
            ],

            'Itel' => [
                'A48',
                'A58',
                'A60',
                'A70',
                'A80',
                'S18',
                'S23',
                'P38',
                'P40',
                'P55',
            ],

            'Nothing' => [
                'Nothing Phone 1',
                'Nothing Phone 2',
                'Nothing Phone 2a',
                'Nothing Phone 2a Plus',
                'Nothing Phone 3a',
                'CMF Phone 1',
                'CMF Phone 2',
                'CMF Watch Pro',
                'CMF Watch Pro 2',
                'Nothing Ear',
            ],

            'Meizu' => [
                'Meizu 16',
                'Meizu 17',
                'Meizu 18',
                'Meizu 18 Pro',
                'Meizu 20',
                'Meizu 20 Pro',
                'Meizu 21',
                'Meizu 21 Pro',
                'Meizu 22',
                'Meizu Note 9',
            ],

            'BlackBerry' => [
                'BlackBerry Classic',
                'BlackBerry Passport',
                'BlackBerry Priv',
                'BlackBerry DTEK50',
                'BlackBerry DTEK60',
                'BlackBerry Motion',
                'BlackBerry KeyOne',
                'BlackBerry Key2',
                'BlackBerry Key2 LE',
                'BlackBerry Z10',
            ],

            'Sharp' => [
                'Aquos R5G',
                'Aquos R6',
                'Aquos R7',
                'Aquos R8',
                'Aquos R9',
                'Aquos Sense6',
                'Aquos Sense7',
                'Aquos Sense8',
                'Aquos Zero2',
                'Aquos Zero6',
            ],

            'Panasonic' => [
                'Eluga A4',
                'Eluga I9',
                'Eluga Ray 500',
                'Eluga Ray 810',
                'Eluga Mark 2',
                'Toughbook CF-33',
                'Toughbook CF-54',
                'Toughbook 55',
                'Lumix S5',
                'Lumix GH6',
            ],

            // =========================================================
            // Laptops & Computers
            // =========================================================

            'Dell' => [
                'XPS 13',
                'XPS 15',
                'XPS 17',
                'Inspiron 15',
                'Inspiron 16',
                'Latitude 5420',
                'Latitude 5520',
                'Precision 5570',
                'Precision 5680',
                'Alienware m18',
            ],

            'HP' => [
                'HP Spectre x360',
                'HP Envy 13',
                'HP Envy 15',
                'HP Pavilion 15',
                'HP Pavilion 16',
                'HP EliteBook 840',
                'HP EliteBook 850',
                'HP ProBook 450',
                'HP Omen 16',
                'HP Victus 16',
            ],

            'Acer' => [
                'Aspire 3',
                'Aspire 5',
                'Aspire 7',
                'Swift 3',
                'Swift Go 14',
                'Swift X',
                'Nitro 5',
                'Nitro 16',
                'Predator Helios 300',
                'Predator Helios Neo 16',
            ],

            'MSI' => [
                'MSI Katana 15',
                'MSI Katana 17',
                'MSI Cyborg 15',
                'MSI Raider GE68',
                'MSI Raider GE78',
                'MSI Stealth 14',
                'MSI Stealth 16',
                'MSI Modern 14',
                'MSI Prestige 16',
                'MSI Creator Z16',
            ],

            'Microsoft' => [
                'Surface Laptop 4',
                'Surface Laptop 5',
                'Surface Laptop 6',
                'Surface Laptop 7',
                'Surface Pro 7',
                'Surface Pro 8',
                'Surface Pro 9',
                'Surface Pro 10',
                'Surface Pro 11',
                'Surface Laptop Studio 2',
            ],

            'Razer' => [
                'Razer Blade 13',
                'Razer Blade 14',
                'Razer Blade 15',
                'Razer Blade 16',
                'Razer Blade 17',
                'Razer Blade 18',
                'Razer Book 13',
                'Razer Blade Stealth',
                'Razer Blade Pro 17',
                'Razer Edge',
            ],

            'Gigabyte' => [
                'AORUS 5',
                'AORUS 7',
                'AORUS 15',
                'AORUS 17',
                'AERO 14',
                'AERO 15',
                'AERO 16',
                'G5 Gaming',
                'G6 Gaming',
                'G7 Gaming',
            ],

            'Fujitsu' => [
                'LIFEBOOK U7312',
                'LIFEBOOK U7412',
                'LIFEBOOK U7512',
                'LIFEBOOK A3511',
                'LIFEBOOK E5512',
                'LIFEBOOK E5412',
                'LIFEBOOK E5513',
                'CELSIUS H5511',
                'CELSIUS H7613',
                'CELSIUS W5010',
            ],

            'Toshiba' => [
                'Dynabook Tecra A40',
                'Dynabook Tecra A50',
                'Dynabook Tecra A60',
                'Dynabook Portege X30',
                'Dynabook Portege X40',
                'Dynabook Satellite Pro C40',
                'Dynabook Satellite Pro C50',
                'Dynabook Satellite Pro C60',
                'Dynabook E10',
                'Dynabook G50',
            ],

            'Medion' => [
                'Erazer Deputy P10',
                'Erazer Deputy P25',
                'Erazer Beast X25',
                'Erazer Beast X40',
                'Erazer Crawler E10',
                'Akoya E154',
                'Akoya E164',
                'Akoya S174',
                'Akoya E153',
                'Akoya S144',
            ],

            'Framework' => [
                'Framework Laptop 13',
                'Framework Laptop 13 AMD',
                'Framework Laptop 13 Intel',
                'Framework Laptop 13 Ryzen',
                'Framework Laptop 16',
                'Framework Laptop 16 Ryzen',
                'Framework Laptop 16 Intel',
                'Framework Chromebook Edition',
                'Framework Laptop 13 DIY',
                'Framework Laptop 16 DIY',
            ],

            'Alienware' => [
                'Alienware m15',
                'Alienware m16',
                'Alienware m17',
                'Alienware m18',
                'Alienware x14',
                'Alienware x15',
                'Alienware x16',
                'Alienware Aurora R13',
                'Alienware Aurora R15',
                'Alienware Aurora R16',
            ],

            // =========================================================
            // TVs & Displays
            // =========================================================

            'Hisense' => [
                'Hisense A6K',
                'Hisense A7K',
                'Hisense E7K',
                'Hisense U6K',
                'Hisense U7K',
                'Hisense U8K',
                'Hisense U6N',
                'Hisense U7N',
                'Hisense U8N',
                'Hisense E7N',
            ],

            'Vizio' => [
                'Vizio V-Series',
                'Vizio D-Series',
                'Vizio M-Series',
                'Vizio M-Series Quantum',
                'Vizio MQX',
                'Vizio P-Series',
                'Vizio P-Series Quantum',
                'Vizio OLED',
                'Vizio OLED Pro',
                'Vizio Quantum Pro',
            ],

            'Skyworth' => [
                'Skyworth 43SUE9500',
                'Skyworth 50SUE9500',
                'Skyworth 55SUE9500',
                'Skyworth 65SUE9500',
                'Skyworth 75SUE9500',
                'Skyworth 43SUE8000',
                'Skyworth 50SUE8000',
                'Skyworth 55SUE8000',
                'Skyworth 65SUE8000',
                'Skyworth XC9300',
            ],

            'Haier' => [
                'Haier H43K800UG',
                'Haier H50K800UG',
                'Haier H55K800UG',
                'Haier H65K800UG',
                'Haier H75K800UG',
                'Haier H43S9UG',
                'Haier H50S9UG',
                'Haier H55S9UG',
                'Haier H65S9UG',
                'Haier H75S9UG',
            ],

            'JVC' => [
                'JVC LT-32KC328',
                'JVC LT-40KC328',
                'JVC LT-43KC328',
                'JVC LT-50KC328',
                'JVC LT-55KC328',
                'JVC LT-58KC328',
                'JVC LT-65KC328',
                'JVC LT-70KC328',
                'JVC LT-75KC328',
                'JVC LT-85KC328',
            ],

            'Vestel' => [
                'Vestel 43UA9630',
                'Vestel 50UA9630',
                'Vestel 55UA9630',
                'Vestel 65UA9630',
                'Vestel 75UA9630',
                'Vestel 43UA9740',
                'Vestel 50UA9740',
                'Vestel 55UA9740',
                'Vestel 65UA9740',
                'Vestel 75UA9740',
            ],

            'Hitachi' => [
                'Hitachi 32HK6000',
                'Hitachi 43HK6000',
                'Hitachi 50HK6000',
                'Hitachi 55HK6000',
                'Hitachi 58HK6000',
                'Hitachi 65HK6000',
                'Hitachi 70HK6000',
                'Hitachi 75HK6000',
                'Hitachi 43HE4000',
                'Hitachi 55HE4000',
            ],

            'Grundig' => [
                'Grundig 32 GHU 7800',
                'Grundig 43 GHU 7800',
                'Grundig 50 GHU 7800',
                'Grundig 55 GHU 7800',
                'Grundig 65 GHU 7800',
                'Grundig 43 GHU 7900',
                'Grundig 50 GHU 7900',
                'Grundig 55 GHU 7900',
                'Grundig 65 GHU 7900',
                'Grundig OLED 65',
            ],

            // =========================================================
            // Cameras
            // =========================================================

            'Canon' => [
                'EOS R5',
                'EOS R5 Mark II',
                'EOS R6',
                'EOS R6 Mark II',
                'EOS R7',
                'EOS R8',
                'EOS R10',
                'EOS 90D',
                'EOS 5D Mark IV',
                'EOS 250D',
            ],

            'Nikon' => [
                'Z5',
                'Z6 II',
                'Z7 II',
                'Z8',
                'Z9',
                'Z30',
                'Z50',
                'D750',
                'D850',
                'D780',
            ],

            'Fujifilm' => [
                'X-T3',
                'X-T4',
                'X-T5',
                'X-S10',
                'X-S20',
                'X100V',
                'X100VI',
                'X-H2',
                'X-H2S',
                'GFX 100 II',
            ],

            'Olympus' => [
                'OM-D E-M1 Mark II',
                'OM-D E-M1 Mark III',
                'OM-D E-M5 Mark II',
                'OM-D E-M5 Mark III',
                'OM-D E-M10 III',
                'OM-D E-M10 IV',
                'PEN E-PL10',
                'PEN E-P7',
                'Tough TG-5',
                'Tough TG-6',
            ],

            'OM System' => [
                'OM-1',
                'OM-1 Mark II',
                'OM-5',
                'OM-3',
                'OM-D E-M10 IV',
                'OM-D E-M1 Mark III',
                'Tough TG-6',
                'Tough TG-7',
                'PEN E-P7',
                'PEN E-PL10',
            ],

            'Leica' => [
                'Leica Q',
                'Leica Q2',
                'Leica Q3',
                'Leica SL2',
                'Leica SL3',
                'Leica M10',
                'Leica M11',
                'Leica M11 Monochrom',
                'Leica CL',
                'Leica TL2',
            ],

            'GoPro' => [
                'HERO8 Black',
                'HERO9 Black',
                'HERO10 Black',
                'HERO11 Black',
                'HERO12 Black',
                'HERO13 Black',
                'HERO7 Black',
                'HERO6 Black',
                'MAX',
                'MAX 360',
            ],

            'DJI' => [
                'DJI Osmo Action',
                'DJI Osmo Action 2',
                'DJI Osmo Action 3',
                'DJI Osmo Action 4',
                'DJI Osmo Action 5 Pro',
                'DJI Pocket 2',
                'DJI Pocket 3',
                'DJI Mini 3 Pro',
                'DJI Mini 4 Pro',
                'DJI Mavic 3 Pro',
            ],

            'Ricoh' => [
                'GR',
                'GR II',
                'GR III',
                'GR IIIx',
                'GR III Diary Edition',
                'GR IIIx Urban Edition',
                'Theta SC2',
                'Theta V',
                'Theta Z1',
                'WG-80',
            ],

            'Pentax' => [
                'K-1',
                'K-1 Mark II',
                'K-3',
                'K-3 II',
                'K-3 Mark III',
                'K-70',
                'KP',
                'KF',
                '645D',
                '645Z',
            ],

            // =========================================================
            // Printers & Scanners
            // =========================================================

            'Epson' => [
                'EcoTank L3250',
                'EcoTank L4260',
                'EcoTank L5290',
                'EcoTank L6270',
                'EcoTank L6490',
                'EcoTank ET-4850',
                'EcoTank ET-5850',
                'WorkForce WF-2930',
                'WorkForce WF-4830',
                'SureColor P700',
            ],

            'Brother' => [
                'DCP-L2540DW',
                'DCP-L2550DW',
                'DCP-L2530DW',
                'HL-L2350DW',
                'HL-L2370DW',
                'MFC-L2710DW',
                'MFC-L2750DW',
                'MFC-L3770CDW',
                'MFC-L3750CDW',
                'MFC-J4335DW',
            ],

            'Lexmark' => [
                'MS321dn',
                'MS421dn',
                'MS521dn',
                'MS621dn',
                'MX321adn',
                'MX421ade',
                'MX521ade',
                'MX622ade',
                'CX421adn',
                'CX522ade',
            ],

            'Xerox' => [
                'WorkCentre 3025',
                'WorkCentre 6515',
                'WorkCentre 6510',
                'VersaLink B400',
                'VersaLink B405',
                'VersaLink C405',
                'VersaLink C500',
                'AltaLink C8030',
                'AltaLink C8045',
                'Phaser 6510',
            ],

            'Kyocera' => [
                'ECOSYS P2040dw',
                'ECOSYS P2235dn',
                'ECOSYS P3155dn',
                'ECOSYS M2040dn',
                'ECOSYS M2135dn',
                'ECOSYS M2635dn',
                'ECOSYS M3645idn',
                'TASKalfa 2554ci',
                'TASKalfa 3253ci',
                'TASKalfa 4054ci',
            ],

            'Konica Minolta' => [
                'bizhub C258',
                'bizhub C257i',
                'bizhub C300i',
                'bizhub C360i',
                'bizhub C4050i',
                'bizhub C450i',
                'bizhub C550i',
                'bizhub C650i',
                'bizhub C250i',
                'bizhub C287',
            ],

            // =========================================================
            // Gaming Consoles
            // =========================================================

            'Nintendo' => [
                'Nintendo Switch',
                'Nintendo Switch OLED',
                'Nintendo Switch Lite',
                'Nintendo Switch 2',
                'Nintendo 3DS',
                'Nintendo 3DS XL',
                'Nintendo 2DS',
                'Nintendo 2DS XL',
                'Wii U',
                'Nintendo Wii',
            ],

            'Valve' => [
                'Steam Deck 64GB',
                'Steam Deck 256GB',
                'Steam Deck 512GB',
                'Steam Deck 1TB',
                'Steam Deck OLED 512GB',
                'Steam Deck OLED 1TB',
                'Steam Controller',
                'Steam Link',
                'Steam Machine',
                'Valve Index',
            ],

            // =========================================================
            // Smart Watches & Wearables
            // =========================================================

            'Garmin' => [
                'Fenix 6',
                'Fenix 7',
                'Fenix 7 Pro',
                'Fenix 8',
                'Forerunner 255',
                'Forerunner 265',
                'Forerunner 965',
                'Venu 2',
                'Venu 3',
                'Instinct 2',
            ],

            'Fitbit' => [
                'Fitbit Charge 4',
                'Fitbit Charge 5',
                'Fitbit Charge 6',
                'Fitbit Versa 2',
                'Fitbit Versa 3',
                'Fitbit Versa 4',
                'Fitbit Sense',
                'Fitbit Sense 2',
                'Fitbit Inspire 2',
                'Fitbit Inspire 3',
            ],

            'Amazfit' => [
                'Amazfit GTR 2',
                'Amazfit GTR 3',
                'Amazfit GTR 3 Pro',
                'Amazfit GTR 4',
                'Amazfit GTS 2',
                'Amazfit GTS 4',
                'Amazfit Balance',
                'Amazfit Active',
                'Amazfit Bip 3',
                'Amazfit T-Rex 2',
            ],

            'Polar' => [
                'Polar Vantage V2',
                'Polar Vantage V3',
                'Polar Vantage M2',
                'Polar Vantage M3',
                'Polar Grit X',
                'Polar Grit X Pro',
                'Polar Grit X2 Pro',
                'Polar Ignite 2',
                'Polar Ignite 3',
                'Polar Pacer Pro',
            ],

            'Casio' => [
                'G-Shock DW-5600',
                'G-Shock GA-2100',
                'G-Shock GA-B2100',
                'G-Shock GBD-200',
                'G-Shock GBD-H2000',
                'G-Shock GW-M5610',
                'G-Shock Mudmaster GG-1000',
                'G-Shock Rangeman GW-9400',
                'Pro Trek PRG-340',
                'Pro Trek PRW-3500',
            ],

            // =========================================================
            // Smart Home & Appliances
            // =========================================================

            'Amazon' => [
                'Echo Dot 4th Gen',
                'Echo Dot 5th Gen',
                'Echo Show 5',
                'Echo Show 8',
                'Echo Show 10',
                'Echo Show 15',
                'Fire TV Stick',
                'Fire TV Stick 4K',
                'Fire TV Cube',
                'Kindle Paperwhite',
            ],

            'Ring' => [
                'Ring Video Doorbell',
                'Ring Video Doorbell 2',
                'Ring Video Doorbell 3',
                'Ring Video Doorbell 4',
                'Ring Video Doorbell Pro',
                'Ring Video Doorbell Pro 2',
                'Ring Stick Up Cam',
                'Ring Indoor Cam',
                'Ring Spotlight Cam',
                'Ring Floodlight Cam',
            ],

            'Philips' => [
                'Philips Hue Bridge',
                'Philips Hue White',
                'Philips Hue Color',
                'Philips Airfryer XXL',
                'Philips Airfryer Essential',
                'Philips Series 3000',
                'Philips Series 5000',
                'Philips Series 7000',
                'Philips OneBlade',
                'Philips Ambilight TV',
            ],

            'Dyson' => [
                'Dyson V7',
                'Dyson V8',
                'Dyson V10',
                'Dyson V11',
                'Dyson V12 Detect Slim',
                'Dyson V15 Detect',
                'Dyson V15s Detect Submarine',
                'Dyson Gen5detect',
                'Dyson Supersonic',
                'Dyson Airwrap',
            ],

            'Bosch' => [
                'Bosch Serie 2 Washing Machine',
                'Bosch Serie 4 Washing Machine',
                'Bosch Serie 6 Washing Machine',
                'Bosch Serie 8 Washing Machine',
                'Bosch Serie 2 Dishwasher',
                'Bosch Serie 4 Dishwasher',
                'Bosch Serie 6 Dishwasher',
                'Bosch Serie 8 Dishwasher',
                'Bosch Serie 4 Refrigerator',
                'Bosch Serie 8 Refrigerator',
            ],

            'Siemens' => [
                'Siemens iQ300 Washing Machine',
                'Siemens iQ500 Washing Machine',
                'Siemens iQ700 Washing Machine',
                'Siemens iQ300 Dishwasher',
                'Siemens iQ500 Dishwasher',
                'Siemens iQ700 Dishwasher',
                'Siemens iQ500 Refrigerator',
                'Siemens iQ700 Refrigerator',
                'Siemens iQ300 Dryer',
                'Siemens iQ700 Dryer',
            ],

            'Miele' => [
                'Miele W1 Washing Machine',
                'Miele W1 TwinDos',
                'Miele T1 Dryer',
                'Miele G5000 Dishwasher',
                'Miele G7000 Dishwasher',
                'Miele K4000 Refrigerator',
                'Miele K7000 Refrigerator',
                'Miele Triflex HX1',
                'Miele Triflex HX2',
                'Miele Blizzard CX1',
            ],

            'Electrolux' => [
                'Electrolux 500 Series Washer',
                'Electrolux 600 Series Washer',
                'Electrolux 700 Series Washer',
                'Electrolux 800 Series Washer',
                'Electrolux 500 Series Dryer',
                'Electrolux 600 Series Dryer',
                'Electrolux UltimateHome 700',
                'Electrolux Pure A9',
                'Electrolux 700 Dishwasher',
                'Electrolux 900 Dishwasher',
            ],

            'Whirlpool' => [
                'Whirlpool WFW5620',
                'Whirlpool WFW6620',
                'Whirlpool WTW5057',
                'Whirlpool WTW8127',
                'Whirlpool WDT750',
                'Whirlpool WDT970',
                'Whirlpool WRS315',
                'Whirlpool WRX735',
                'Whirlpool WRF535',
                'Whirlpool WRT318',
            ],

            'Midea' => [
                'Midea Xtreme Save',
                'Midea Mission Pro',
                'Midea Breezeless',
                'Midea Breezeless E',
                'Midea PrimeGuard',
                'Midea Cube',
                'Midea PortaSplit',
                'Midea U-Shaped AC',
                'Midea Inverter AC',
                'Midea MultiZone AC',
            ],

            'Gree' => [
                'Gree Fairy',
                'Gree Pular',
                'Gree Bora',
                'Gree Amber',
                'Gree U-Crown',
                'Gree Lomo',
                'Gree Viola',
                'Gree Clivia',
                'Gree Muse',
                'Gree Change',
            ],

            'Arçelik' => [
                'Arçelik 7103',
                'Arçelik 9103',
                'Arçelik 9123',
                'Arçelik 9146',
                'Arçelik 10120',
                'Arçelik 12325',
                'Arçelik 12560',
                'Arçelik 274580',
                'Arçelik 570475',
                'Arçelik 9500',
            ],

            'Beko' => [
                'Beko WTV 8744',
                'Beko WTV 8734',
                'Beko WMY 91443',
                'Beko WMY 10143',
                'Beko DIN 28430',
                'Beko DIN 48430',
                'Beko RCNE 560',
                'Beko RCNA 406',
                'Beko DFS 050',
                'Beko DFN 28430',
            ],

            'Mitsubishi Electric' => [
                'MSZ-AP',
                'MSZ-LN',
                'MSZ-EF',
                'MSZ-HR',
                'MSZ-RW',
                'MSZ-DW',
                'MSZ-AY',
                'MSZ-BT',
                'MSZ-FT',
                'MSZ-SF',
            ],

            'NEC' => [
                'MultiSync EA242F',
                'MultiSync EA272F',
                'MultiSync E273F',
                'MultiSync E243W',
                'MultiSync PA243W',
                'MultiSync PA271Q',
                'MultiSync PA322UHD',
                'MultiSync EX341R',
                'MultiSync UN552S',
                'MultiSync UN552VS',
            ],
        ];

        foreach ($brands as $brandName => $models) {

            $brand = Brand::where('brand_name', $brandName)->first();

            if (!$brand) {
                continue;
            }

            foreach ($models as $modelName) {
                DeviceModel::updateOrCreate(
                    [
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                    ],
                    [
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                    ]
                );
            }
        }
    }
}