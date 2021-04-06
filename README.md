# Nozomu Investment with Laravel

Dibuat oleh : 
    Rafif Ridho


### Instalasi backend

1. Buka terminal atau gitbash

2. git clone pada folder yang diinginkan repository seperti ini :

```
git clone https://github.com/okanemo/Rafif-Ridho
```

3. cd ke direktori yang sudah di clone :

```
cd Rafif-Ridho
```

4. Buka xampp, dan start apache dan MySql

5. Buka phpmyadmin dan create new tabel dengan nama nozomu

6. Kembali ke terminal dan jalankan :

```
composer install
```

7. Download .env.example dari repository dan simpan di project laravel yang sudah diclone. Kemudian rename filenya menjadi .env

8. Pada terminal ketikkan :

```
php artisan key:generate
```

```
php artisan migrate:fresh
```

```
php artisan serve
```
9. Sebelum memulai menggunakan api backendnya , insert data pada tabel NAB sebagai default apabila tidak ada nasabah seperti gambar di bawah ini 

!(insertDefaultNAB)[/img/1.png]

### REST API Endpoint

1. Menambahkan/Registrasi Nasabah/user

* pada postman pilih http method POST dan sertakan url :

```
http://localhost:8000/api/v1/user/add
```
* pada kolom body, masukkan data nama, u_name, email, password, password_confirmation, dan balance dan klik send

* nantinya akan muncul detail user dan token,copy token untuk digunakan pada endpoint lainnya

2. Update total Balance

* pada postman pilih http method POST dan sertakan url : 

```
http://localhost:8000/api/v1/ib/updateTotalBalance
```

* pada kolom body, masukkan jumlah balance yang ingin dimasukkan ke total balance

* sebelum klik send, pada kolom authorization pilih opsi bearer token lalu copykan token yang berasal ketika registrasi, baru klik send.

3. Melihat List NAB

* pada postman pilih http method GET dan sertakan url :

```
http://localhost:8000/api/v1/lib/listNAB
```

* Akan muncul history pergantian angka NAB

4. Top Up Balance

* pada postman pilih http method POST dan sertakan url :

```
http://localhost:8000/api/v1/lib/topup/id
```

* pada url id diatas tuliskan id user yang telah diregistrasi

* pada kolom body masukkan jumlah amount yang diinginkan untuk topup

* klik send maka akan muncul updated balance pada response

5. Withdraw Balance

* pada postman pilih http method POST dan sertakan url :

```
http://localhost:8000/api/v1/lib/withdraw/id
```

* pada url id diatas tuliskan id user yang telah diregistrasi

* pada kolom body masukkan jumlah amount yang diinginkan untuk withdraw, pastikan jumlah withdraw tidak melebihi saldo balance nasabah

* klik send maka akan muncul updated balance pada response

6. Melihat detail semua nasabah

* pada postman pilih http method GET dan sertakan url:

```
http://localhost:8000/api/v1/ib/member
```

* klik send maka akan muncul respon berupa json user, nilai nab saat ini dan total keseuruhan balance user











