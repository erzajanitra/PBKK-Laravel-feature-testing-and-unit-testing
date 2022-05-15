# Feature Testing
## Pengertian
Feature test dapat digunakan untuk menguji gabungan komponen pada aplikasi yang kita buat. Test ini dilakukan untuk memastikan sistem pada aplikasi yang kita buat dapat berjalan sesuai fungsionalitasnya. Terdapat 2 cara untuk melakukan feature testing yang akan kita coba, yaitu :
1. HTTP Test </br>
   Laravel menyediakan API yang dapat digunakan untuk melakukan HTTP request kepada aplikasi yang kita miliki untuk mendapatkan HTTP response.
2. Browser Test </br>
   Browser test dilakukan terhadap sebuah aplikasi dengan menggunakan browser dan semua test case yang sudah di tulis akan dijalankan oleh Laravel Dusk secara otomatis
   

## Tutorial
### HTTP Test
Untuk melakukan HTTP Test dapat menjalankan command berikut
```php artisan make:test PostTest```

Maka pada project laravel akan terdapat folder `tests` dan sub folder `Feature` dengan beberapa script di dalamnya

<img width="164" alt="image" src="https://user-images.githubusercontent.com/75319371/168455939-ce12cd21-657b-473c-9511-665af528faf7.png">
